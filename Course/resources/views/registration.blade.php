@extends('layouts.app')

@section('header')
<h1>Регистрация</h1>
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

<form action="{{route('register-user')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name" @class(['label alert' => $errors->has('name')])>Имя</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name">
    <label for="email" @class(['label alert' => $errors->has('email')])>Почта</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email">
    <label for="login" @class(['label alert' => $errors->has('login')])>Логин</label>
    <input id="login" type="text" name="login" value="{{ old('login') }}" autocomplete="nickname">
    <label for="password" @class(['label alert' => $errors->has('password')])>Пароль</label>
    <input id="password" type="password" name="password" autocomplete="new-password">
    <label for="password_confirmation" @class(['label alert' => $errors->has('password_confirmation')])>Подтвердите пароль</label>
    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
    <label for="image" @class(['label alert' => $errors->has('image')])>Изображение</label>
    <input id="image" name="image" type="file">
    <button type="submit">Зарегистрироваться</button>
</form>

<p>Есть аккаунт? <a href="{{route('login')}}">Войти</a></p>

@endsection