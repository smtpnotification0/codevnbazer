<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'variation_id',
        'order_id',
        'transaction_id',
        'code',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'code' => 'array',
    ];

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
}
