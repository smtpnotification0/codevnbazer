<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupToOf extends Model
{
    use HasFactory;

    // টেবিলের নাম যদি মাইগ্রেশনে অন্য কিছু থাকে তবে এখানে বলে দিতে পারেন
    protected $table = 'topup_to_of';

    // যে কলামগুলো ডাটাবেসে সেভ হবে
    protected $fillable = [
        'status',
        'balance_detect',
        'player_id_1',
        'player_id_2',
        'player_id_3',
        'player_id_4',
        'player_id_5',
    ];

    // স্ট্যাটাস কলামটিকে অটোমেটিক boolean এ রূপান্তর করতে
    protected $casts = [
        'status' => 'boolean',
    ];
}