<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $request->session()->forget('url.intended');

        if (session('just_registered')) {
            session()->forget('just_registered');
            return redirect()->route('mypage.edit');
        }


        return redirect()->route('home');
    }
}