@extends ('layout')

@section('content')
<div class="row">
<h1>Личный кабинет</h1>
</div>
<div class="frfr" style='margin-left:500px;'>
<p>Фильтр:</p>
<ul>
  <li><a href="{{route('lk', ['login' => auth()->user()->login] )}}?filter=active">Активные</a></li>
  <li><a href="{{route('lk', ['login' => auth()->user()->login] )}}?filter=past">Прошедшие</a></li>
  <li><a href="{{route('lk', ['login' => auth()->user()->login] )}}?filter=empty">Нет мест</a></li>
  <li><a href="{{route('lk', ['login' => auth()->user()->login] )}}">Без фильтра</a></li>
</ul>
@error('error')
  @foreach ($errors->all() as $error)
  <p class="pps" style="color:red;">{{ $error }}</p>
  @endforeach
@enderror
@foreach ($courses as $one)
          <article class="blog_post">
          
             <div class="three columns">
            @if(auth() && auth()->user() != null)
            <a href="{{ route ('in', [$one->course_id, Auth::user()->id]) }}" class="th">
            @else
            <a href="{{ route ('in', $one->course_id) }}" class="th">
            @endif
             @if($one->course->image)
             <img src="{{ asset ('storage/' . $one->course->image) }}">
             @else
             <img src="{{ asset ('storage/images/default.png') }}">
             @endif
             </a>
             </div>
             <div class="nine columns">
              @if(auth() && auth()->user() != null)
              <a href="{{ route ('in', [$one->course_id, Auth::user()->id]) }}"><h4>{{ $one->course->title}}</h4></a>
              @else
              <a href="{{ route ('in', $one->course_id) }}"><h4>{{ $one->course->title}}</h4></a>
              @endif
              <p>{{ $one->course->lid }}</p>
              @auth
              <form action="{{ route('unsub', $one->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
              <button class="button" style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#e57373; color:#FFFFFF; border:none;">Отписаться</button>
              </form>
              @endauth
              </div>
          </article>
        @endforeach
</div>
@endsection