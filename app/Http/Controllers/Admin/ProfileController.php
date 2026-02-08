<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view(
            'admin.profile',
            ['user' => $request->user()]
        );
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'username' =>
                'required|string|max:50|unique:users,username,' . $user->id,
        ]);

        $user->update($data);

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with(
            'success',
            'Password changed successfully.'
        );
    }
}
