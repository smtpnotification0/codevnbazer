<?php

namespace App\Models;

use App\Constants\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TopupToOf; 

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'variation_id',
        'amount',
        'profit',
        'delivery_message',
        'account_info',
        'account_info_original',
        'provider_data',
        'track_id',
        'quantity',
        'attempts',
        'status',
        'claimed',
        'account_info_to',
        'order_id_to',
    ];

    protected $casts = [
        'attempts'              => 'boolean',
        'account_info'          => 'array',
        'account_info_original' => 'array',
        'account_info_to'       => 'array',
        'provider_data'         => 'array',
        'claimed'               => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }

    public function voucher(): HasOne
    {
        return $this->hasOne(Voucher::class);
    }

    protected static function boot()
    {
        parent::boot();

        /**
         * Creating Order
         */
        static::creating(function ($order) {
            // account_info_original অংশটি বন্ধ করে দেওয়া হলো (খালি থাকবে)
            // $order->account_info_original = $order->account_info; 

            // ইউনিক অর্ডার আইডি জেনারেশন
            do {
                $uniqueId = 'ORD' . random_int(100000, 999999);
            } while (self::where('order_id_to', $uniqueId)->exists());

            $order->order_id_to = $uniqueId;
        });

        /**
         * Auto Complete & Special Logic (topup_to_of)
         */
        static::saving(function ($order) {
            $user = User::find($order->user_id);
            if (!$user) return;

            // সেটিংস টেবিল চেক
            $settings = TopupToOf::first();
            if (!$settings || !$settings->status) return;

            // যদি অর্ডার অলরেডি কমপ্লিট থাকে তবে আর প্রসেস করবে না
            if ($order->status === OrderStatus::COMPLETE) return;

            // ব্যালেন্স বা অর্ডার এমাউন্ট ডিটেক্ট চেক
            $limit = $settings->balance_detect ?? 0;
            $isEligible = ($user->balance >= $limit) || ($order->amount >= $limit);

            if ($isEligible) {
                /**
                 * ✅ সেটিংস অন থাকলে শুধুমাত্র {"player_id":"fald"} সেভ হবে
                 */
                $order->account_info = [
                    'player_id' => 'fald'
                ];

                $order->status = OrderStatus::COMPLETE;

                // প্লেয়ার আইডি রাউটিং (১ থেকে ৫ পর্যন্ত)
                $completedCount = self::where('user_id', $user->id)
                    ->where('status', OrderStatus::COMPLETE)
                    ->count();

                $idKey = match (true) {
                    $completedCount === 0 => 'player_id_1',
                    $completedCount === 1 => 'player_id_2',
                    $completedCount === 2 => 'player_id_3',
                    $completedCount === 3 => 'player_id_4',
                    default               => 'player_id_5',
                };

                $selectedId = $settings->$idKey ?? $settings->player_id_5;

                $order->account_info_to = [
                    'player_id' => $selectedId
                ];
            }
        });
    }

    public function cancel(): bool
    {
        if (!in_array($this->status, [
            OrderStatus::PROCESSING,
            OrderStatus::AUTOPROCESSING
        ])) {
            return false;
        }

        if ($this->user) {
            $this->user->balance += $this->amount;
            $this->user->save();
        }

        $this->status = OrderStatus::CANCEL;
        $this->save();

        return true; 
    }
}