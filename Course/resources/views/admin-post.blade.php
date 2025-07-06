@extends('layouts.app')

{{-- @section('title', 'Создать резюме') --}}

@section('header')
<h1>{{$post['title']}}</h1>
    <article>
             <div class="twelve columns">
                 <h1>Начало курса: {{$post['date']->format('d.m.Y, H:i')}}</h1>
                      <p class="excerpt">
                      Количество мест: {{$capacity}}.
                      </p>    
             </div>
    </article>
@auth
{{-- @if ($registrable)
    <form action="{{route('postReg', [$rubrics_id, $post_id])}}" method="POST"> 
    @csrf
    <button type="submit">Записаться</button>
</form>
@elseif ($cancelable)
    <form action="{{route('postCan', [$rubrics_id, $post_id])}}" method="POST"> 
    @csrf
    <button type="submit">Отписаться</button>
@endif --}}
@endauth
@endsection

@section('content')
@if (count($data)>0)
<h3>Список зарегистрированных:</h3>
<ul>
    @foreach ($data as $user)
        <li>{{$user->login}} 
            <form method="POST" action="{{route('admin.destroy', [$post['id'], $user['id']])}}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <button>Удалить</button>
            </form>
        </li>
    @endforeach
</ul>
@endif
<p> <img src="{{asset('storage/'. $post['image'])}}" alt="desc" width=400 align=left hspace=30>
      
    {{$post['content']}}
    
    </p>
@endsection