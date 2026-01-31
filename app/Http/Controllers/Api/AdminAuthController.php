<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'sometimes|boolean',
        ]);

        $admin = User::where('username', $request->username)
            ->where('role', 'admin')
            ->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $remember = $request->boolean('remember_me');

        // ⏳ Token expiry logic
        $expiresAt = $remember
            ? now()->addDays(30)     // remember me
            : now()->addHours(8);   // normal login

        $token = $admin->createToken(
            'admin-api',
            ['*'],
            $expiresAt
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $expiresAt->toDateTimeString(),
            'remember_me' => $remember,
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
            ]
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (! $user->is_active) {
            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Account is inactive'
            ], 403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'token_expires_at' => optional(
                $user->currentAccessToken()
            )->expires_at,
        ]);
    }
}
