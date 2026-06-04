<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Constants\OrderStatus;

class ReferralController extends Controller
{
    /**
     * ✅ Referral Claim Function
     * Note: Coins are now automatically added when orders complete, so this is kept for backward compatibility
     */
    public function claim(Request $request)
    {
        // Coins are automatically added when orders complete, so no manual claim needed
        return redirect()->back()->with('info', "Coins are automatically added when your referrals complete orders.");
    }

    /**
     * ✅ Referral Page Function
     * Shows referral link, coin-to-taka rate, etc.
     */
    public function refer()
    {
        $user = Auth::user();

        // Get coin to taka rate from settings (new format: just the taka amount)
        $takaAmount = \App\Models\Setting::get('coin_to_taka') ?? '7';
        
        // Handle old format (1000=7) if exists
        if (strpos($takaAmount, '=') !== false) {
            [, $takaAmount] = explode('=', $takaAmount);
        }
        
        $takaPerUnit = (float) $takaAmount;
        $coinsPerTaka = 1000; // Always 1000 coins base

        return view('pages.refer', compact('user', 'coinsPerTaka', 'takaPerUnit'));
    }

    /**
     * ✅ Redeem Coins Function
     * ইউজার কয়েনকে টাকায় কনভার্ট করবে
     */
    public function redeemCoins(Request $request)
    {
        $request->validate([
            'coins' => 'required|integer|min:1000'
        ]);

        $user = Auth::user();
        $coinsToRedeem = (int) $request->coins;

        if ($user->coins < $coinsToRedeem) {
            return redirect()->back()->with('error', "You don't have enough coins.");
        }

        // Get coin to taka rate from settings (new format: just the taka amount)
        $takaAmount = \App\Models\Setting::get('coin_to_taka') ?? '7';
        
        // Handle old format (1000=7) if exists
        if (strpos($takaAmount, '=') !== false) {
            [, $takaAmount] = explode('=', $takaAmount);
        }
        
        $takaPerUnit = (float) $takaAmount;
        $coinsPerTaka = 1000; // Always 1000 coins base

        // Calculate how many taka user will get
        $redeemableTaka = floor($coinsToRedeem / $coinsPerTaka) * $takaPerUnit;

        if ($redeemableTaka <= 0) {
            return redirect()->back()->with('error', "Invalid coin amount for redemption.");
        }

        // Update user balance and coins
        $user->balance += $redeemableTaka;
        $user->coins -= $coinsToRedeem;
        $user->save();

        return redirect()->back()->with('success', "You have successfully redeemed ৳{$redeemableTaka} using {$coinsToRedeem} coins!");
    }
}