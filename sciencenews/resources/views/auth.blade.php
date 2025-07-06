@extends('layout')

@section('content')
<div class="FU" style="padding:200px;">
</div>
<form action="" method="POST">
    @csrf
    @method('')
    <fieldset style="width:360px; margin: auto; font-family: Open Sans Condensed;">
        <legend style="color:#2BA6CB; background-color:#FAFAFA; font-size:18pt;">Вход</legend>
        <div class="FU" style="padding:40px 30px 40px 30px; font-size:14pt;">
        Логин:
        <input type="text" name="username" style="width:300px;">
        Пароль:
        <input type="text" name="password" style="width:300px;">
        <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:300px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Войти</button>
        </div>
    </fieldset>
</form>
<div class="FU" style="padding:200px;">
@endsection
