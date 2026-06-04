<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'image_url',
        'url',
        'content',
        'button_text',
        'type',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
