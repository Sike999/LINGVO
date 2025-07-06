@extends('layout')

@section('content')
@if ($errors->any())
<header>
   

        <div class="row">
        
        <h1>Новый курс</h1>
        
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
    <p class="pps">Заголовок:</p>
    <input type="text" name="title" value="{{ old('title')}}">
    <p class="pps">Заглавное предложение:</p>
    <input type="text" name='lid' value="{{ old('lid')}}">
    <textarea name="content">{{ old('content') }}</textarea>
    <p class="pps">Язык:</p>
    <select name="rubric_id" value="{{ old('rubric_id')}}">
        @foreach($rubrics as $rub)
        <option value="{{ $rub->id }}">{{ $rub->name }}</option>
        @endforeach
    </select>
    <p class="pps">Дата начала:</p>
    <input type="date" name="date" value="{{ old('date') }}">
    <p class="pps">Кол-во мест:</p>
    <input type="text" name="capacity" value="{{ old('capacity') }}">
    <p class="pps">Добавьте картинку</p>
    <input type="file" name="image">
    <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none;">Добавить</button>
</form>
</div>
@else
<header>
   

        <div class="row">
        
        <h1>Новый курс</h1>
        
</header>

<div class="row">
<form action="{{ route('create.confirm') }}" method="POST" enctype="multipart/form-data" style="font-family: Open Sans Condensed;">
    @csrf
    @method('PUT')
    <p class="pps">Заголовок:</p>
    <input type="text" name="title">
    <p class="pps">Первое предложение:</p>
    <input type="text" name='lid'>
    <textarea name="content"></textarea>
    <p class="pps">Язык:</p>
    <select name="rubric_id">
        @foreach($rubrics as $rub)
        <option value="{{ $rub->id }}">{{ $rub->name }}</option>
        @endforeach
    </select>
    <p class="pps">Дата начала:</p>
    <input type="date" name="date">
    <p class="pps">Кол-во мест:</p>
    <input type="text" name="capacity">
    <p class="pps">Добавьте картинку</p>
    <input type="file" name="image">
    <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none;">Добавить</button>
</form>
</div>
@endif
@endsection