<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'user_gmail',
        'method',
        'transaction_id',
        'amount',
        'page',
        'order_id',
        'time_paid',
        'unpaid',
    ];

    public $timestamps = false; 

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
}