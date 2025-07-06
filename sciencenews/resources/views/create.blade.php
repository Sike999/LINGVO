@extends('layout')

@section('content')
@if ($errors->any())
<header>
   

        <div class="row">
        
        <h1>Новый пост</h1>
        
</header>

    @foreach($errors->all() as $error)
        <ul style="width:300px; margin:0 auto;">
        <li>{{ $error }}</li>
        </ul>
    @endforeach

<div class="row">
<form action="{{ route('create.confirm') }}" method="POST" enctype="multipart/form-data" style="font-family: Open Sans Condensed;">
    @csrf
    @method('PUT')
    <p class="pps">Ваш заголовок:</p>
    <input type="text" name="link" value="{{ old('link')}}">
    <p class="pps">Ваше заглавное предложение:</p>
    <input type="text" name='head' value="{{ old('head')}}">
    <textarea name="text">{{ old('text') }}</textarea>
    <p class="pps">Категория поста:</p>
    <select name="cat_id" value="{{ old('cat_id')}}">
        @foreach($cat as $c)
        <option value="{{ $c->id }}">{{ $c->category }}</option>
        @endforeach
    </select>
    <p class="pps">Добавьте картинку</p>
    <input type="file" name="img">
    <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Добавить</button>
</form>
</div>
@else
<header>
   

        <div class="row">
        
        <h1>Новый пост</h1>
        
</header>

<div class="row">
<form action="{{ route('create.confirm') }}" method="POST" enctype="multipart/form-data" style="font-family: Open Sans Condensed;">
    @csrf
    @method('PUT')
    <p class="pps">Ваш заголовок:</p>
    <input type="text" name="link">
    <p class="pps">Первое предложение:</p>
    <input type="text" name='head'>
    <textarea name="text"></textarea>
    <p class="pps">Категория поста:</p>
    <select name="cat_id">
        @foreach($cat as $c)
        <option value="{{ $c->id }}">{{ $c->category }}</option>
        @endforeach
    </select>
    <p class="pps">Добавьте картинку</p>
    <input type="file" name="img">
    <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Добавить</button>
</form>
</div>
@endif
@endsection