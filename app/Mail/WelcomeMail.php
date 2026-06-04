public function signup(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|unique:users',
        'password' => 'required|confirmed|min:6',
        'terms' => 'accepted',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
        'picture' => 'https://ui-avatars.com/api/?name=' . urlencode(substr($request->name, 0, 1)) . '&size=96&background=f29f2c',
        'user_type' => '0',
        'balance' => '0',
        'total_order' => '0',
        'total_spent' => '0',
        'status' => '1',
    ]);

    if ($user) {
        // ✅ সরাসরি ইউজারকে লগইন করানো হচ্ছে
        Auth::login($user);
        return redirect()->route('home');
    }

    return back()->with('message', 'Something went wrong')->with('message_type', 'error');
}