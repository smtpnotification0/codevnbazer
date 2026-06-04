<?php

namespace App\Http\Controllers\Gateway;

use App\Library\UddoktaPay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user   = Auth::user();
        $amount = (float) $request->amount;

        // ৫-সেকেন্ড anti-double-click lock
        $lockKey = 'deposit_lock:' . $user->id . ':' . $amount;
        if (!Cache::add($lockKey, 1, 5)) {
            return back()->with('message', 'একই amount এ duplicate deposit request। দয়া করে ৫ সেকেন্ড পর আবার চেষ্টা করুন।')
                ->with('message_type', 'warning');
        }

        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email,
            'amount'       => $amount,
            'metadata'     => [
                'amount'  => $amount,
                'user_id' => $user->id,
                'type'    => 'deposit',
            ],
            'redirect_url' => route('payment'),
            'return_type'  => 'GET',
            'cancel_url'   => route('cancel.payment'),
        ];

        try {
            $paymentUrl = UddoktaPay::init_payment($requestData);
            return redirect($paymentUrl);
        } catch (Exception $e) {
            return back()->with('message', 'Payment Error: ' . $e->getMessage())->with('message_type', 'error');
        }
    }

    public function payment(Request $request)
    {
        $invoiceId = $request->invoice_id ?? $request->transactionId;

        if (empty($invoiceId)) {
            return redirect()->route('addfunds')
                ->with('message', 'Invalid Request: No ID found.')
                ->with('message_type', 'error');
        }

        // verify lock (একই invoice parallel hit আটকাতে)
        $verifyLock = 'deposit_verify:' . md5($invoiceId);
        if (!Cache::add($verifyLock, 1, 30)) {
            return redirect()->route('addfunds')
                ->with('message', 'এই deposit আগে থেকেই process হচ্ছে।')
                ->with('message_type', 'warning');
        }

        try {
            $data = UddoktaPay::verify_payment($invoiceId);

            if (!isset($data['status']) || $data['status'] !== 'COMPLETED') {
                return redirect()->route('addfunds')
                    ->with('message', 'Add money failed.')
                    ->with('message_type', 'error');
            }

            $amount        = $data['amount'] ?? ($data['metadata']['amount'] ?? 0);
            $user          = Auth::user() ?? \App\Models\User::find($data['metadata']['user_id']);
            $paymentMethod = $data['payment_method'] ?? 'UddoktaPay';
            $gatewayTrxId  = $data['transaction_id'] ?? $invoiceId;

            if (!$user)    throw new Exception("User not found.");
            if ($amount <= 0) throw new Exception("Invalid amount.");

            // ----- ATOMIC duplicate guard (race-safe) -----
            $tx = null;
            $alreadyExisted = false;

            DB::transaction(function () use ($user, $amount, $gatewayTrxId, $paymentMethod, &$tx, &$alreadyExisted) {
                $tx = Transaction::firstOrCreate(
                    ['transaction_id' => $gatewayTrxId],
                    [
                        'user_id'    => $user->id,
                        'user_gmail' => $user->email,
                        'method'     => $paymentMethod,
                        'amount'     => $amount,
                        'page'       => 'add fund page',
                        'order_id'   => null,
                        'time_paid'  => now(),
                        'unpaid'     => 0,
                    ]
                );

                if (!$tx->wasRecentlyCreated) {
                    $alreadyExisted = true;
                    return;
                }

                // lockForUpdate দিয়ে balance update — race safe
                $u = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();
                $u->increment('balance', $amount);
            });

            if ($alreadyExisted) {
                return redirect()->route('addfunds')
                    ->with('message', 'This transaction has already been processed.')
                    ->with('message_type', 'warning');
            }

            $this->sendNotification("💰 Deposit Successful!\nEmail: {$user->email}\nAmount: {$amount} BDT\nMethod: {$paymentMethod}\nTrx ID: {$gatewayTrxId}");

            return redirect()->route('addfunds')
                ->with('message', 'Add money success.')
                ->with('message_type', 'success');

        } catch (Exception $e) {
            \Log::error('Deposit Error: ' . $e->getMessage());
            return redirect()->route('addfunds')
                ->with('message', 'Error: ' . $e->getMessage())
                ->with('message_type', 'error');
        }
    }

    public function payment_cancel(Request $request)
    {
        return redirect()->route('home')
            ->with('message', 'Payment Canceled.')
            ->with('message_type', 'error');
    }

    private function sendNotification($message)
    {
        try {
            $settings = app(\App\Settings\GeneralSettings::class);
            if ($settings->botToken_2 && $settings->chatId_2) {
                Http::timeout(5)->post("https://api.telegram.org/bot{$settings->botToken_2}/sendMessage", [
                    'chat_id' => $settings->chatId_2,
                    'text'    => $message,
                ]);
            }
        } catch (Exception $e) {
            \Log::error("Telegram Notify Error: " . $e->getMessage());
        }
    }
}
