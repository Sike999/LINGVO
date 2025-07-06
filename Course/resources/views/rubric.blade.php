@extends('layouts.app')

@section('header')
<h1>Создание рубрики</h1>
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

<form action="{{route('store-rubric')}}" method="POST">
    @csrf
    <label for="name">Название рубрики</label>
    <input id="name" type="text" name="name" value="{{old('name')}}" autocomplete="off">
    <button type="submit">Отправить</button>
</form>

@endsection

