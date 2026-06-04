<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        if (request()->has('ref')) {
            session(['referral_from' => request()->get('ref')]);
            session()->save();
        }

        // stateless = mobile / safari / incognito এ "Invalid state" error হবে না
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            try {
                $googleUser = Socialite::driver('google')->stateless()->user();
            } catch (InvalidStateException $e) {
                // fallback retry
                $googleUser = Socialite::driver('google')->stateless()->user();
            }

            if (!$googleUser || !$googleUser->getEmail()) {
                return redirect('/login')
                    ->with('message', 'Google থেকে email পাওয়া যায়নি।')
                    ->with('message_type', 'error');
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name'          => $googleUser->getName() ?? 'User',
                    'email'         => $googleUser->getEmail(),
                    'phone'         => null,
                    'password'      => bcrypt(Str::random(16)),
                    'picture'       => $googleUser->getAvatar(),
                    'user_type'     => 'user',
                    'balance'       => 0,
                    'total_order'   => 0,
                    'total_spent'   => 0,
                    'is_reseller'   => 0,
                    'status'        => 1,
                ]);

                $ref = session('referral_from');
                if ($ref) {
                    $referrer = User::where('referral_code', $ref)->first();
                    if ($referrer) {
                        $user->referred_by = $referrer->id;
                        $user->save();
                    }
                    session()->forget('referral_from');
                }
            } else {
                // existing user update
                $user->name    = $googleUser->getName() ?? $user->name;
                $user->picture = $googleUser->getAvatar() ?? $user->picture;
                $user->save();
            }

            Auth::login($user, true);
            return redirect()->intended(route('account'));

        } catch (\Throwable $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')
                ->with('message', 'Google login failed: ' . $e->getMessage())
                ->with('message_type', 'error');
        }
    }
}
