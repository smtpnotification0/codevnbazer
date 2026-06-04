<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Constants\OrderStatus;

class UserController extends Controller
{
    // Registration page
    public function register()
    {
        return view('auth.register');
    }

    // Signup with referral handling
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $firstLetter = strtoupper(substr(trim($request->name), 0, 1));
        $profilePicture = "https://ui-avatars.com/api/?name=" . urlencode($firstLetter) . "&size=96&background=f29f2c";

        // Check referral
        $referrerUser = null;
        if ($request->filled('referral_code')) {
            $referrerUser = User::where('referral_code', $request->referral_code)->first();
        }

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'picture'      => $profilePicture,
            'user_type'    => 'user',
            'balance'      => 0,
            'total_order'  => 0,
            'total_spent'  => 0,
            'coins'        => 0,
            'status'       => 1,
            'referred_by'  => $referrerUser ? $referrerUser->id : null,
        ]);

        // Note: total_refer is incremented in User model's created event

        Auth::login($user);
        return redirect()->route('home')->with('message', 'Registration successful!');
    }

    // Login page
    public function login()
    {
        return view('auth.login');
    }

    // Signin
    public function signin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('home');
        }

        return back()->withErrors(['credential' => 'Your credentials do not match our records.']);
    }

    // Forget password page
    public function forget()
    {
        return view('auth.forget');
    }

    // Send reset password email
    public function forget_password(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $token = Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        Mail::send('emails.password-reset', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Password Reset Link');
        });

        return back()->with('message', 'Password reset email sent successfully.')->with('message_type', 'success');
    }

    // Reset password page
    public function resetpassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Reset password process
    public function reset_password(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token'    => 'required',
        ]);

        $resetData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetData || !Hash::check($request->token, $resetData->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('message', 'Password updated successfully.')->with('message_type', 'success');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Account page
    public function account()
    {
        $user = Auth::user();
        return view('pages.account', compact('user'));
    }

    // Redeem referral coins
    public function redeemReferralCoins()
    {
        $user = Auth::user();
        $coins = $user->claimReferralCoins();

        if ($coins > 0) {
            return back()->with('message', "You have successfully redeemed {$coins} coins.");
        } else {
            return back()->with('message', "No coins available to redeem.")->with('message_type', 'info');
        }
    }
}