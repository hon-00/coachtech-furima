@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')

<div class="content">
    <div class="content-wrapper">
        <h2 class="content-title">会員登録</h2>
        <form class="content-form" action="{{ route('register.store') }}" method="post">
            @csrf
            <p class="form-item">ユーザー名</p>
            <input class="form-input" type="text" name="name">
            <p class="form-item">メールアドレス</p>
            <input class="form-input" type="text" name="email">
            <p class="form-item">パスワード</p>
            <input class="form-input" type="text" name="password">
            <p class="form-item">確認用パスワード</p>
            <input class="form-input" type="text" name="password_confirmation">
            <button class="form-button" type="submit">登録する</button>
        </form>
        <a class="content-link" href="" >ログインはこちら</a>
    </div>
</div>
@endsection