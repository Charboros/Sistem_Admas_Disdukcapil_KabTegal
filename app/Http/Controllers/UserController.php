<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function store(\App\Http\Requests\StoreUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(\App\Http\Requests\UpdateUserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus admin.');
        }
        
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
