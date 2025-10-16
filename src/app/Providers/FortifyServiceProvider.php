<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest as CustomLoginRequest;
use App\Http\Responses\LoginResponse as CustomLoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(LoginResponseContract::class, CustomLoginResponse::class);
    }

    public function boot()
    {
        $this->app->bind(
            FortifyLoginRequest::class,
            CustomLoginRequest::class
        );

        Fortify::loginView(fn () => view('auth.login'));

        Fortify::authenticateUsing(function (Request $request) {

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => 'ログイン情報が登録されていません',
        ]);
    });

        // 新規登録画面
        Fortify::registerView(fn () => view('auth.register'));

        // ユーザー登録処理
        Fortify::createUsersUsing(CreateNewUser::class);
    }
}