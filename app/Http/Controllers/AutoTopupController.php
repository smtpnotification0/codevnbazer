<?php

namespace App\Http\Controllers;

use App\Constants\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutoTopupController extends Controller
{
    /**
     * =====================================
     * MAIN AUTO TOPUP UPDATE WEBHOOK
     * =====================================
     */
    public function update(Request $request)
    {
        /**
         * ===============================
         * 1️⃣ SIMPLE API SUPPORT
         * ===============================
         */
        if ($request->has('orderid')) {
            $orderId = $request->input('orderid');
            $status  = $request->input('status');
            $content = $request->input('content');

            $order = Order::find($orderId);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $user = $order->user;

            // ✅ SUCCESS
            if ($status === 'success') {
                $order->status = OrderStatus::COMPLETE;
                $order->save();
                return response()->json(['message' => 'Order marked as completed']);
            }

            // default processing
            $order->status = OrderStatus::PROCESSING;
            $order->voucher_code = $content;
            $order->save();

            // ❌ INVALID UID / REGION
            if (in_array($content, ['Invalid Player ID', 'region does not match'])) {
                $order->status = OrderStatus::CANCEL;

                if ($content === 'Invalid Player ID') {
                    $order->delivery_message =
                        'আপনার (UID) ভুল হয়েছে। টাকা আপনার ওয়ালেটে ফেরত দেওয়া হয়েছে। দয়া করে সঠিক UID দিয়ে অর্ডার করুন।';
                } else {
                    $order->delivery_message =
                        'শুধুমাত্র বাংলাদেশ সার্ভারে ডায়মন্ড নেওয়া যাবে।';
                }

                $order->save();

                if ($user) {
                    $refund = $order->amount ?? 0;
                    $user->increment('balance', $refund);
                    $user->decrement('total_order');
                    $user->decrement('total_spent', $refund);
                }
            }

            // ⚠ ERROR BALANCE
            if ($content === 'error_balance') {
                $order->status = OrderStatus::PROCESSING;
                $order->delivery_message =
                    'দুঃখিত, আমাদের automatic service এ সমস্যা হয়েছে। আমাদের এডমিন অর্ডারটি চেক করে সম্পন্ন করে দিবে।';
                $order->save();
            }

            return response()->json(['message' => 'Order updated successfully']);
        }

        /**
         * ===============================
         * 2️⃣ JSON WEBHOOK SUPPORT
         * ===============================
         */
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $parseBuffer = function ($array) use (&$parseBuffer) {
            foreach ($array as $key => $value) {
                if (is_array($value) && ($value['type'] ?? '') === 'Buffer') {
                    $array[$key] = base64_encode(implode(array_map("chr", $value['data'])));
                } elseif (is_array($value)) {
                    $array[$key] = $parseBuffer($value);
                }
            }
            return $array;
        };

        $data = $parseBuffer($data);

        $status = $data['data']['status'] ?? null;
        $orderState = $data['data']['orderState'] ?? [];
        $message = strtolower($orderState['orderFailedMessage'] ?? '');

        $orderId = $request->query('order');
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (in_array($order->status, [OrderStatus::COMPLETE, OrderStatus::CANCEL])) {
            return response()->json(['message' => 'Order already processed']);
        }

        if (in_array($status, ['success', 'finish'])) {
            $order->status = OrderStatus::COMPLETE;
            $order->save();
        }

        if (in_array($status, ['failed', 'error'])) {
            $order->status = OrderStatus::PROCESSING;
            $order->save();
        }

        if (
            str_contains($message, 'invalid uid') ||
            str_contains($message, 'invalid player') ||
            str_contains($message, 'invalid region') ||
            str_contains($message, 'not bd server')
        ) {
            $order->status = OrderStatus::CANCEL;
            $order->delivery_message = 'শুধুমাত্র বাংলাদেশ সার্ভারে ডায়মন্ড নেওয়া যাবে।';
            $order->save();

            if ($order->user) {
                $order->user->increment('balance', $order->amount);
                $order->user->decrement('total_order');
                $order->user->decrement('total_spent', $order->amount);
            }
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }

    /**
     * =====================================
     * HUMAYUN BOT WEBHOOK (বাংলা message)
     * =====================================
     */
    public function humayunWebhook(Request $request)
    {
        try {
            $data = $request->all();

            if (!isset($data['order_id'], $data['status'])) {
                return response()->json(['error' => 'Missing fields'], 400);
            }

            $order = Order::find($data['order_id']);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $status  = strtolower($data['status']);
            $message = strtolower($data['message'] ?? '');

            $isInvalidUid = (
                str_contains($message, 'invalid uid') ||
                str_contains($message, 'invalid region') ||
                str_contains($message, 'not bd server') ||
                str_contains($message, 'wrong uid')
            );

            $isCanceledByAdmin = str_contains($message, 'canceled by admin');

            if ($status === 'failed' && ($isInvalidUid || $isCanceledByAdmin)) {

                if (in_array($order->status, [OrderStatus::PROCESSING, OrderStatus::AUTOPROCESSING])) {
                    $order->user?->increment('balance', $order->amount);
                }

                $order->status = OrderStatus::CANCEL;

                if ($isInvalidUid) {
                    $order->delivery_message = 'শুধুমাত্র বাংলাদেশ সার্ভারে ডায়মন্ড নেওয়া যাবে।';
                } else {
                    $order->delivery_message =
                        'অর্ডারটি এডমিন দ্বারা বাতিল করা হয়েছে। টাকা ওয়ালেটে ফেরত দেওয়া হয়েছে।';
                }

                $order->save();

                return response()->json(['message' => 'Order canceled']);
            }

            if (in_array($status, ['completed', 'complete'])) {
                $order->status = OrderStatus::COMPLETE;
                $order->save();
            }

            return response()->json(['message' => 'Webhook processed']);
        } catch (\Exception $e) {
            Log::error('Humayun webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * =====================================
     * AUTOMATION BOT WEBHOOK (বাংলা message)
     * =====================================
     */
    public function automationWebhook(Request $request)
    {
        try {
            $data = $request->all();

            if (!isset($data['order_id'], $data['status'])) {
                return response()->json(['error' => 'Missing fields'], 400);
            }

            $order = Order::find($data['order_id']);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $status  = strtolower($data['status']);
            $message = strtolower($data['message'] ?? '');

            if (
                $status === 'failed' &&
                (
                    str_contains($message, 'invalid uid') ||
                    str_contains($message, 'invalid region') ||
                    str_contains($message, 'not bd server') ||
                    str_contains($message, 'canceled by admin')
                )
            ) {
                if (in_array($order->status, [OrderStatus::PROCESSING, OrderStatus::AUTOPROCESSING])) {
                    $order->user?->increment('balance', $order->amount);
                }

                $order->status = OrderStatus::CANCEL;
                $order->delivery_message = str_contains($message, 'canceled by admin')
                    ? 'অর্ডারটি এডমিন দ্বারা বাতিল করা হয়েছে। টাকা ওয়ালেটে ফেরত দেওয়া হয়েছে।'
                    : 'শুধুমাত্র বাংলাদেশ সার্ভারে ডায়মন্ড নেওয়া যাবে।';

                $order->save();

                return response()->json(['message' => 'Order canceled']);
            }

            if (in_array($status, ['completed', 'complete'])) {
                $order->status = OrderStatus::COMPLETE;
                $order->save();
            }

            return response()->json(['message' => 'Webhook processed']);
        } catch (\Exception $e) {
            Log::error('Automation webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}