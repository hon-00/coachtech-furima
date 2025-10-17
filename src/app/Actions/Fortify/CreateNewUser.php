<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Session;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input)
    {
        // FormRequest を使って検証
        $request = app(RegisterRequest::class);
        $request->merge($input);
        $request->validateResolved();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        Session::put('just_registered', true);

        return $user;
    }
}