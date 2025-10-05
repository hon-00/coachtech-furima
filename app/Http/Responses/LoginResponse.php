<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->is_first_login) {
            $user->update(['is_first_login' => false]);

            return redirect()->route('profile.edit');
        }

        return redirect()->route('home');
    }
}