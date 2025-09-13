<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'  => Hash::make($request->password),
            'profile_image'=> null,
        ]);

        auth()->login($user);

        return redirect()->route('mypage.profile')
                        ->with('success', '登録が完了しました！');
    }
}
