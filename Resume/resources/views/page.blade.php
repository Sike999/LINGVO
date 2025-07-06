@extends('layout')

@section('title')
{{ $header }}
@endsection

@section('content')
<div class="leftcol">
@foreach($res as $r)
<p class="pinline second">
@if($r->Image)
     <img src="{{ asset('storage/' . $r->Image) }}" alt="Фото" style="width: 160px; height: 160px;">
@else
     <img src="{{ asset('storage/default.png') }}" alt="Нет фото" style="width: 160px; height: 160px;">
@endif
<p class='candidates'>{{ $r->FIO }}</p>
<span  class="pinline third">
     {{ $r->staff->staff }}
</span>
<br>
<span  class="pinline third">
Стаж: {{ $r->Stage }}
</span>
<br>
Телефон: {{ $r->Phone }}
</p>
<a href="{{ route('update', $r->id) }}"><button class="button">Изменить</button></a>
<br>
<br>
<form action="{{ route('delete', $r->id) }}" method="POST">
    @csrf
    @method('DELETE')
<button class="button">Удалить</button>
</form>
<hr>
@endforeach
</div>
@endsection