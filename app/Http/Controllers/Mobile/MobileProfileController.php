<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function edit()
    {
        return view('mobile.profile');
    }

    /**
     * Update username
     */
    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|max:50|unique:users,username,' . auth()->id(),
        ]);

        auth()->user()->update([
            'username' => $request->username,
        ]);

        return back()->with('success', 'Username updated successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (! Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
