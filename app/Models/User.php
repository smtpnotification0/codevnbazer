<?php

namespace App\Models;

use Filament\Panel;
use App\Constants\OrderStatus;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'picture', 'user_type',
        'balance', 'total_order', 'total_spent', 'coins', 'is_reseller',
        'status', 'referral_code', 'referred_by', 'total_refer',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_reseller' => 'boolean',
        'status' => 'boolean',
        'coins' => 'integer',
        'total_refer' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                do {
                    $code = strtoupper(Str::random(8));
                } while (self::where('referral_code', $code)->exists());
                $user->referral_code = $code;
            }

            if (!isset($user->coins)) {
                $user->coins = 0;
            }

            if (!isset($user->total_refer)) {
                $user->total_refer = 0;
            }
        });

        // Update total_refer on new user creation
        static::created(function ($user) {
            if ($user->referred_by) {
                $referrer = self::find($user->referred_by);
                if ($referrer) {
                    $referrer->increment('total_refer');
                }
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->user_type === 'admin';
    }

    public function getRedirectRoute()
    {
        return match ($this->user_type) {
            'user' => '/',
            'admin' => '/admin'
        };
    }

    public function isAdmin() { return $this->user_type === 'admin'; }
    public function isUser() { return $this->user_type === 'user'; }
    public function isBanned() { return (int) $this->status === 0; }
    public function isReseller() { return (int) $this->is_reseller === 1; }

    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function referrer() { return $this->belongsTo(self::class, 'referred_by'); }
    public function referrals(): HasMany { return $this->hasMany(self::class, 'referred_by'); }

    public function getReferralLinkAttribute()
    {
        $appUrl = config('app.url') ?? url('/');
        return $appUrl . '/register?ref=' . $this->referral_code;
    }

    public function addCoins(int $amount): void
    {
        $this->coins += $amount;
        $this->save();
    }

    public function deductCoins(int $amount): void
    {
        $this->coins = max(0, $this->coins - $amount);
        $this->save();
    }

    /**
     * Get total coins earned from referrals
     */
    public function getTotalReferralCoins(): int
    {
        // This is just for display - coins are automatically added when orders complete
        return $this->coins;
    }
}