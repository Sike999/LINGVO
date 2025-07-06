@extends('layout')

@section('title', 'Мое резюме')

@section('content')
<div class="leftcol">
<div class="pinline1">
	<img class="pic" src="{{ asset('Images/ava' . $id . '.jpg') }}">
</div>

<p class="pinline second">
{{$name}}
<br>
Телефон: 
{{$phone}}</p>

<p  class="pinline third">
Программист
<br>
Стаж: 
{{$experience}}</p>
</div>
@endsection