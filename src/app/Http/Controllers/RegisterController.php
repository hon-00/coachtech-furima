<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'postal_code'  => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'  => Hash::make($request->password),
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'profile_image'=> null,
        ]);

        // Log the user in
        auth()->login($user);

        return redirect('/')->with('success', '登録が完了しました！');
    }
}
