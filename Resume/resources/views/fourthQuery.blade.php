@extends('layout')

@section('title')
{{ 'Профессии, представители которых имеются в резюме' }}
@endsection

@section('content')
<table>
    <tr><th>Профессии</th></tr>
@foreach($res as $r)
    <tr> 
        <td>{{ $r->staff }}</td>
    </tr>
@endforeach
<table>
@endsection