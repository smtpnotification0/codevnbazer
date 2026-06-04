<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'categorie_id',
        'title',
        'slug',
        'content',
        'image',
        'type',
        'percentage',
        'uid_checker',
        'status',
        'input',
        'slot',
        'has_tutorial',
        'tutorial_link',
        'tutorial_text',
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'has_tutorial' => 'boolean',
        'uid_checker' => 'integer',
        'slot' => 'integer',
    ];
    
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    public function isVoucher(): bool
    {
        return $this->type === Status::VOUCHER;
    }

    public function isInGame(): bool
    {
        return $this->type === Status::INGAME;
    }

    public function isTopup(): bool
    {
        return $this->type === Status::TOPUP;
    }
    
    public function isSubscription(): bool
    {
        return $this->type == Status::SUBSCRIPTION;
    }
}
