@extends('layout')

@section('title')
{{ 'Фамилии и стаж людей с профессией Программист' }}
@endsection

@section('content')
<table>
    <tr><th>Фамилии</th><th>Стаж</th></tr>
@foreach($res as $r)
    <tr> 
        <td>{{ $r->FIO }}</td>
        <td>{{ $r->Stage }} лет</td>
    </tr>
@endforeach
<table>
@endsection