@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')

<div class="content">
    <h2 class="content-title">会員登録</h2>
    <form class="content-form" action="" method="post">
        @csrf
        <label class="form-label" for="">
            <input class="form-input" type="text">
        </label>
        <label class="form-label" for="">
            <input class="form-input" type="text">
        </label>
        <label class="form-label" for="">
            <input class="form-input" type="text">
        </label>
    </form>
</div>