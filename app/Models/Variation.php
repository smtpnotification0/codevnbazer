<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'price',
        'gift_coins',
        'buy_rate',
        'stock',
        'automatic',
        'provider',
        'provider_product_id',
    ];

    protected $casts = [
        'automatic' => 'boolean',
        'status'    => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function isAutomatic(): bool
    {
        return $this->automatic;
    }

    public function providerType(Order $order)
    {
        $provider = "App\\Services\\TopupProvider\\{$this->provider}";
        return new $provider($order);
    }
}
