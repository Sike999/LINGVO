@extends('layout')

@section('title')
{{ 'Фамилии персон, имеющих стаж от 5 до 15 лет' }}
@endsection

@section('content')
<table>
    <tr><th>Фамилии</th></tr>
@foreach($res as $r)
    <tr>
        <td>{{ $r->FIO }}</td>
    </tr>
@endforeach
</table>
@endsection