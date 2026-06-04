<?php

namespace App\Models;

use App\Constants\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    // ✅ নতুন: Transaction relationship যোগ করা হয়েছে
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }

    public function cancel(): bool
    {
        if (!in_array($this->status, [
            OrderStatus::PROCESSING,
            OrderStatus::AUTOPROCESSING,
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