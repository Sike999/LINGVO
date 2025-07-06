@extends('layouts.app')

@section('header')
<h1>Вход</h1>
@endsection

@section('content')

@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
@endif

<form action="{{route('login-user')}}" method="POST">
    @csrf
    <label for="login" @class(['alert' => $errors->has('login')])>Логин</label>
    <input id="login" type="text" name="login" value="{{ old('login') }}" autocomplete="nickname">
    <label for="password" @class(['label alert' => $errors->has('password')])>Пароль</label>
    <input id="password" type="password" name="password" autocomplete="current-password">
    <button type="submit">Войти</button>
</form>

<p>Нет аккаунта? <a href="{{route('registration')}}">Зарегистрироваться</a></p>

@endsection

