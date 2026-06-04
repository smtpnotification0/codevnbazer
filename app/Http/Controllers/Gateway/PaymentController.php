<?php

namespace App\Http\Controllers\Gateway;

use App\Library\UddoktaPay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function deposit(Request $request) {
    
      $request->validate([
          'amount' => 'required|numeric|min:1'
      ]);
      
      $user = Auth::user();
      $amount = $request->amount;
      
      $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email,
            'amount'       => $amount,
            'metadata'     => [
                'amount' => $amount,
                'user_id' => $user->id,
                'type' => 'deposit'
            ],
            'redirect_url'  => route('payment'),
            'return_type'   => 'GET',
            'cancel_url'    => route('cancel.payment'),
        ];

        try {
            $paymentUrl = UddoktaPay::init_payment($requestData);
            return redirect($paymentUrl);
        } catch (Exception $e) {
            return back()->with('message', 'Payment Error: ' . $e->getMessage())->with('message_type', 'error');
        }
    }
    
    public function payment(Request $request) {
        // UddoktaPay থেকে আসা ইনভয়েস আইডি বা ট্রানজেকশন আইডি
        $invoiceId = $request->invoice_id ?? $request->transactionId;

        if (empty($invoiceId)) {
            return redirect()->route('addfunds')->with('message', 'Invalid Request: No ID found.')->with('message_type', 'error');
        }

        // ডুপ্লিকেট ট্রানজেকশন চেক
        $exists = Transaction::where('transaction_id', $invoiceId)->exists();
        if ($exists) {
            return redirect()->route('addfunds')->with('message', 'This transaction has already been processed.')->with('message_type', 'warning');
        }

        try {
            $data = UddoktaPay::verify_payment($invoiceId);

            if (isset($data['status']) && $data['status'] == 'COMPLETED') {
                
                $amount = $data['amount'] ?? $data['metadata']['amount'];
                $user = Auth::user() ?? \App\Models\User::find($data['metadata']['user_id']);
                
                // গেটওয়ে থেকে আসা অরিজিনাল ট্রানজেকশন আইডি এবং মেথড
                $paymentMethod = $data['payment_method'] ?? 'UddoktaPay';
                $gatewayTrxId = $data['transaction_id'] ?? $invoiceId; // অরিজিনাল আইডি

                if (!$user) {
                    throw new Exception("User not found.");
                }

                return DB::transaction(function () use ($user, $amount, $gatewayTrxId, $paymentMethod, $data) {
                    // ১. ইউজারের ব্যালেন্স বাড়ানো
                    $user->increment('balance', $amount);

                    // ২. ট্রানজেকশন টেবিলে সঠিক ডাটা সেভ করা
                    Transaction::create([
                        'user_id'        => $user->id,
                        'user_gmail'     => $user->email,
                        'method'         => $paymentMethod,    // bKash/Nagad/Rocket ইত্যাদি বসবে
                        'transaction_id' => $gatewayTrxId,    // UddoktaPay থেকে আসা আসল আইডি বসবে
                        'amount'         => $amount,
                        'page'           => 'add fund page',
                        'order_id'       => null, 
                        'time_paid'      => now(),
                        'unpaid'         => 0,
                    ]);

                    // ৩. টেলিগ্রামে নোটিফিকেশন পাঠানো
                    $this->sendNotification("💰 Deposit Successful!\nEmail: {$user->email}\nAmount: {$amount} BDT\nMethod: {$paymentMethod}\nTrx ID: {$gatewayTrxId}");

                    return redirect()->route('addfunds')->with('message', 'Add money success.')->with('message_type', 'success');
                });

            } else {
                return redirect()->route('addfunds')->with('message', 'Add money failed.')->with('message_type', 'error');
            }

        } catch (Exception $e) {
            \Log::error('Deposit Error: ' . $e->getMessage());
            return redirect()->route('addfunds')->with('message', 'Error: ' . $e->getMessage())->with('message_type', 'error');
        }
    }
    
    public function payment_cancel(Request $request) {
      return redirect()->route('home')->with('message', 'Payment Canceled.')->with('message_type', 'error');
    }

    private function sendNotification($message)  
    {  
        $settings = app(\App\Settings\GeneralSettings::class);
        if ($settings->botToken_2 && $settings->chatId_2) {
            try {
                Http::post("https://api.telegram.org/bot{$settings->botToken_2}/sendMessage", [ 
                    'chat_id' => $settings->chatId_2, 
                    'text'    => $message 
                ]);
            } catch (Exception $e) { }
        }
    }
}