<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileAuthController extends Controller
{
    public function showLogin()
    {
        return view('mobile.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'delivery_boy',
            'is_active' => 1,
        ])) {
            return back()
                ->withErrors([
                    'username' => 'Invalid credentials or inactive account',
                ])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('mobile.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mobile.login');
    }
}
