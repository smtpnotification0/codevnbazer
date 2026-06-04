<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerName extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'data_updated_at',
    ];

    protected $casts = [
        'data_updated_at' => 'datetime',
    ];
}

