<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // শুধু ইউজারের নাম দেখানো হবে
        return view('user.transactions', compact('user'));
    }
}