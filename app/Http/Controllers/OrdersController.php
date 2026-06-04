<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Constants\OrderStatus;
use App\Constants\TopupProvider;
use App\Library\UddoktaPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;
use App\Models\Order;
use App\Models\Variation;
use App\Models\Voucher;
use App\Models\Transaction;

class OrdersController extends Controller
{
    public function buynow(Request $request)
    {
        $variation = Variation::where('stock', '>', 0)
            ->with(['product', 'vouchers' => function ($query) {
                $query->where('status', Status::AVAILABLE);
            }])
            ->findOrFail($request->variation_id);

        $quantity = $request->input('quantity', 1);

        if ($variation->product->isVoucher() && $variation->vouchers->count() < $quantity) {
            return back()->with('error', __('Sorry, this voucher is out of stock.'));
        }

        $amount_cal = round($variation->price * $quantity, 2);
        $profit_cal = number_format(max(0, $amount_cal - ($variation->buy_rate * $quantity)), 2, '.', '');

        $orderData = [
            'user_id'               => Auth::id(),
            'product_id'            => $variation->product->id,
            'variation_id'          => $variation->id,
            'quantity'              => $quantity,
            'amount'                => $amount_cal,
            'profit'                => $profit_cal,
            'account_info'          => $request->input('account_info'),
            'account_info_original' => $request->input('account_info'),
            'account_info_to'       => $request->input('account_info'), 
        ];

        // Wallet Payment Logic
        if (gs()->wallet && $request->payment_method === Status::WALLET) {
            try {
                if ($amount_cal > Auth::user()->balance) {
                    throw new Exception(__('Insufficient Balance.'));
                }

                $vouchers = null;
                if ($variation->product->isVoucher()) {
                    $vouchers = Voucher::where('status', Status::AVAILABLE)
                        ->where('variation_id', $variation->id)
                        ->limit($quantity)
                        ->orderBy('id', 'DESC')
                        ->get();

                    if ($vouchers->count() < $quantity) {
                        throw new Exception(__('Insufficient vouchers available.'));
                    }
                }

                DB::transaction(function () use ($orderData, $vouchers, $amount_cal) {
                    $order = Order::create($orderData);
                    $order->status = $order->product->isVoucher() ? Status::COMPLETE : Status::PROCESSING;
                    $order->save();

                    $user = $order->user;
                    $user->decrement('balance', $order->amount);

                    Transaction::create([
                        'user_id'        => $user->id,
                        'user_gmail'     => $user->email,
                        'method'         => 'Wallet',
                        'transaction_id' => 'WAL' . strtoupper(Str::random(10)),
                        'amount'         => $amount_cal,
                        'page'           => 'check out page',
                        'order_id'       => $order->id,
                        'time_paid'      => now(),
                        'unpaid'         => 0,
                    ]);

                    if ($order->product->isVoucher()) {
                        $variation = $order->variation;
                        $variation->decrement('stock', $vouchers->count());

                        $voucherCodes = [];
                        foreach ($vouchers as $voucher) {
                            $voucherCodes[] = is_array($voucher->code) ? implode(',', $voucher->code) : $voucher->code;
                            $voucher->status = Status::SOLD;
                            $voucher->order_id = $order->id;
                            $voucher->save();
                        }
                        $order->voucher_code = implode(', ', $voucherCodes);
                        $order->save();
                    } else {
                        $order->variation->decrement('stock', $order->quantity);
                    }

                    $this->handleReseller($order);
                    $this->triggerAutomation($order);
                });

                $redirect = $variation->product->isVoucher() ? route('codes') : route('orders');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => true, 'redirect_url' => $redirect, 'message' => 'Order Successful.']);
                }
                return redirect($redirect)->with('message', 'Order Successful.')->with('message_type', 'success');

            } catch (Exception $exception) {
                $this->sendNotification("⚠️ Wallet Payment Failed!\nUser: " . Auth::user()->email . "\nError: " . $exception->getMessage());
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $exception->getMessage()], 400);
                }
                return back()->with('message', $exception->getMessage())->with('message_type', 'error');
            }
        }

        return $this->processUddoktaPay($variation, $orderData, $request);
    }

    public function paymentSuccess(Request $request)
    {
        $transactionId = $request->query('transactionId') ?? $request->query('invoice_id');
        if (empty($transactionId)) {
            return redirect()->route('orders')->with('message', 'Order failed: Transaction ID missing.')->with('message_type', 'error');
        }

        try {
            $data = UddoktaPay::verify_payment($transactionId);
            if (isset($data['status']) && $data['status'] === 'COMPLETED') {
                $metadata = is_array($data['metadata']) ? $data['metadata'] : json_decode($data['metadata'], true);
                if (!$metadata || ($metadata['type'] ?? null) !== 'order') {
                    return redirect()->route('orders')->with('message', 'Invalid metadata.')->with('message_type', 'error');
                }

                $variation = Variation::with('product')->findOrFail($metadata['variation_id']);
                $user = Auth::user() ?? \App\Models\User::find($metadata['user_id']);
                if (!$user) throw new Exception("User not found.");

                $gatewayTrxId = $data['transaction_id'] ?? $transactionId;
                $paymentMethod = $data['payment_method'] ?? 'UddoktaPay';

                if (Transaction::where('transaction_id', $gatewayTrxId)->exists()) {
                    return redirect()->route('orders')->with('message', 'Order already processed.');
                }

                return DB::transaction(function () use ($variation, $metadata, $gatewayTrxId, $paymentMethod, $user) {
                    $amount_cal = round($variation->price * $metadata['quantity'], 2);
                    $profit_cal = number_format(max(0, $amount_cal - ($variation->buy_rate * $metadata['quantity'])), 2, '.', '');

                    $order = Order::create([
                        'user_id'               => $user->id,
                        'product_id'            => $variation->product->id,
                        'variation_id'          => $variation->id,
                        'quantity'              => $metadata['quantity'],
                        'amount'                => $amount_cal,
                        'profit'                => $profit_cal,
                        'account_info'          => $metadata['account_info'],
                        'account_info_original' => $metadata['account_info'],
                        'account_info_to'       => $metadata['account_info'],
                        'status'                => $variation->product->isVoucher() ? Status::COMPLETE : Status::PROCESSING
                    ]);

                    Transaction::create([
                        'user_id'        => $user->id,
                        'user_gmail'     => $user->email,
                        'method'         => $paymentMethod,
                        'transaction_id' => $gatewayTrxId,
                        'amount'         => $amount_cal,
                        'page'           => 'check out page',
                        'order_id'       => $order->id,
                        'time_paid'      => now(),
                        'unpaid'         => 0,
                    ]);

                    if ($order->product->isVoucher()) {
                        $vouchers = Voucher::where('status', Status::AVAILABLE)->where('variation_id', $variation->id)->limit($order->quantity)->get();
                        $codes = [];
                        foreach ($vouchers as $v) {
                            $v->update(['status' => Status::SOLD, 'order_id' => $order->id]);
                            $codes[] = $v->code;
                        }
                        $order->update(['voucher_code' => implode(', ', $codes)]);
                    }
                    $variation->decrement('stock', $order->quantity);

                    $this->handleReseller($order);
                    $this->triggerAutomation($order);

                    return redirect($variation->product->isVoucher() ? route('codes') : route('orders'))->with('message', 'Order Successful.')->with('message_type', 'success');
                });
            }
            return redirect()->route('orders')->with('message', 'Payment not completed.')->with('message_type', 'error');
        } catch (Exception $e) {
            $this->sendNotification("⚠️ UddoktaPay Verification Failed!\nError: " . $e->getMessage());
            return redirect()->route('orders')->with('message', 'Verification Error: ' . $e->getMessage())->with('message_type', 'error');
        }
    }

    private function processUddoktaPay($variation, $orderData, $request)
    {
        try {
            $user = Auth::user();
            $requestData = [
                'full_name'    => $user->name ?? 'Guest User',
                'email'        => $user->email ?? 'no-email@test.com',
                'amount'       => $orderData['amount'],
                'metadata'     => [
                    'account_info' => $request->input('account_info'),
                    'variation_id' => $variation->id,
                    'quantity'     => $request->input('quantity', 1),
                    'user_id'      => $user->id,
                    'type'         => 'order'
                ],
                'redirect_url' => route('payment.success'),
                'return_type'  => 'GET',
                'cancel_url'   => route('cancel.payment'),
            ];

            $paymentUrl = UddoktaPay::init_payment($requestData);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'payment_url' => $paymentUrl]);
            }
            return redirect($paymentUrl);
        } catch (Exception $e) {
            $this->sendNotification("⚠️ UddoktaPay Init Failed!\nUser: " . Auth::user()->email . "\nError: " . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->with('message', $e->getMessage())->with('message_type', 'error');
        }
    }

    private function handleReseller(Order $order)
    {
        $user = $order->user;
        if ($user && method_exists($user, 'isReseller') && $user->isReseller()) {
            $percentageAmount = ($order->amount * $order->product->percentage) / 100;
            $user->increment('balance', $percentageAmount);
        }
    }

    private function triggerAutomation($order)
    {
        try {
            if ($order->product->isTopup() && $order->variation->isAutomatic() && gs()->enable_auto_topup && $order->status === Status::PROCESSING) {
                // এখানে আগে Humayun Webhook ছিল, এখন আপনার নতুন API কল হবে
                $this->transferToNewApi($order);
            } else {
                $this->sendNotification("✅ New Order Received!\nOrder ID: #{$order->id}\nVariation: {$order->variation->title}");
            }
        } catch (Exception $e) {
            \Log::error("Automation Error: " . $e->getMessage());
            $this->sendNotification("❌ Automation Error!\nOrder ID: #{$order->id}\nError: " . $e->getMessage());
        }
    }

    /**
     * আপনার নতুন ওয়েবসাইটে অর্ডার ট্রান্সফার করার লজিক
     */
    private function transferToNewApi(Order $order)
{
    // UID এক্সট্রাক্ট করা
    $uid = $order->account_info['player_id'] ?? $order->account_info;
    
    // পেলোড আপডেট করা হয়েছে (order_id যুক্ত করা হয়েছে)
    $payload = [
        'order_id'         => (string) $order->id, // আপনার বর্তমান সিস্টেমের আইডি
        'package_tagline'  => $order->variation->provider_product_id ?? '', 
        'account_info'     => $uid,
    ];

    $apiUrl = "https://demo1.oktopupbd.fun/api/orders/receive";

    try {
        // রিকোয়েস্টের সাথে এই ওয়েবসাইটের লিংক (Origin) পাঠানো হচ্ছে
        $response = Http::withHeaders([
            'Referer' => url('/'), 
            'Accept'  => 'application/json',
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            // সফল হলে ডাটাবেজ আপডেট
            $order->update(['status' => OrderStatus::AUTOPROCESSING]);
        } else {
            // ট্রান্সফার ফেইল হলে টেলিগ্রাম নোটিফিকেশন
            $errorBody = $response->body();
            $this->sendNotification("❌ API Transfer Failed!\nOrder ID: #{$order->id}\nStatus: " . $response->status() . "\nError: " . $errorBody);
        }
    } catch (\Exception $e) {
        \Log::error("API Transfer Error: " . $e->getMessage());
        // কানেকশন এরর হলেও টেলিগ্রাম নোটিফিকেশন
        $this->sendNotification("⚠️ API Connection Error!\nOrder ID: #{$order->id}\nError: " . $e->getMessage());
    }
}
    private function sendNotification($message)
    {
        try {
            $settings = app(\App\Settings\GeneralSettings::class);
            if ($settings->botToken_1 && $settings->chatId_1) {
                Http::post("https://api.telegram.org/bot{$settings->botToken_1}/sendMessage", [
                    'chat_id' => $settings->chatId_1, 
                    'text' => $message
                ]);
            }
        } catch (Exception $e) { \Log::error("Telegram Notify Error: " . $e->getMessage()); }
    }
}