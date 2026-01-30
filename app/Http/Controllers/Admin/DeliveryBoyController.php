<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeliveryBoyController extends Controller
{
    public function index()
    {
        $deliveryBoys = User::where('role', 'delivery_boy')->latest()->get();

        return view('admin.delivery_boys.index', compact('deliveryBoys'));
    }

    public function create()
    {
        return view('admin.delivery_boys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'delivery_boy',
        ]);

        return redirect()
            ->route('admin.delivery_boys.index')
            ->with('success', 'Delivery Boy created successfully');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role !== 'delivery_boy') {
            abort(403);
        }

        $user->update([
            'is_active' => ! $user->is_active
        ]);

        return back()->with('success', 'Delivery boy status updated');
    }
}
