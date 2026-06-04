<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'url',
        'image_url',
        'order_column',
        'status',
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
}
