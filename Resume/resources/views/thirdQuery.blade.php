@extends('layout')

@section('title')
{{ 'Общее число резюме в базе' }}
@endsection

@section('content')
    <span style="font-size:60px;">Общее количество резюме: {{ $res }}</span>
@endsection