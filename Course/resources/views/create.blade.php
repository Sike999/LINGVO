@extends('layouts.app')

@section('header')
<h1>Создание курса</h1>
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

<form action="{{route('store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="title">Название</label>
    <input id="title" type="text" name="title" value="{{ old('title') }}" autocomplete="off">
    <label for="lid">Краткое описание</label>
    <input id="lid" type="text" name="lid" value="{{ old('lid') }}" autocomplete="off">
    <label for="content">Контент</label>
    <input id="content" type="text" name="content" value="{{ old('content') }}" autocomplete="off">
    <label for="rubric">Рубрика</label>
    <select id="rubric" name="rubric_id">
        @foreach ($rubrics as $rubric)
            <option value="{{$rubric['id']}}" @selected(old('rubric_id') === $rubric['id'])>{{$rubric['name']}}</option>
        @endforeach
    </select>
    <label for="date">Дата начала</label>
    <input id="date" type="datetime-local" name="date" value="{{ old('date') }}" autocomplete="off">
    <script>
        document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    </script>
    <label for="capacity">Количество участников</label>
    <input id="capacity" type="text" name="capacity" value="{{old('capacity')}}" autocomplete="off">
    <label for="image">Изображение</label>
    <input id="image" name="image" type="file">
    <button type="submit">Отправить</button>
</form>

@endsection

