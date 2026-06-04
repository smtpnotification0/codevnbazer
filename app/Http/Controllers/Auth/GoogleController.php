<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // store referral in session if exists
        if (request()->has('ref')) {
            session(['referral_from' => request()->get('ref')]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // find existing user
            $user = User::where('email', $googleUser->getEmail())->first();

            // -------------------------------
            // CREATE NEW USER
            // -------------------------------
            if (!$user) {

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'phone' => null,
                    'password' => bcrypt(Str::random(12)), // FIXED: always hashed
                    'picture' => $googleUser->getAvatar(),
                    'user_type' => 'user',
                    'balance' => 0,
                    'total_order' => 0,
                    'total_spent' => 0,
                    'is_reseller' => 0,
                    'status' => 1,
                ]);

                // -------------------------------
                // REFERRAL HANDLING
                // -------------------------------
                $ref = session('referral_from');
                if ($ref) {
                    $referrer = User::where('referral_code', $ref)->first();

                    if ($referrer) {
                        $user->referred_by = $referrer->id;
                        $user->save();
                        // Note: total_refer is incremented in User model's created event
                    }

                    session()->forget('referral_from');
                }

                Auth::login($user);
                return redirect()->route('account');
            }

            // -------------------------------
            // UPDATE EXISTING USER
            // -------------------------------
            $user->name = $googleUser->getName();
            $user->picture = $googleUser->getAvatar() ?? $user->picture;
            $user->save();

            Auth::login($user);
            return redirect()->route('account');

        } catch (\Exception $e) {

            Log::error('Google Login Error: ' . $e->getMessage());

            return redirect('/login')
                ->with('message', 'Google login canceled or failed.')
                ->with('message_type', 'error');
        }
    }
}