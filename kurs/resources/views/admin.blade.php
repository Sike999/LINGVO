@extends ('layout')

@section('content')
<div class="row">
<h1>Админ панель</h1>
</div>
@error('error')
  @foreach ($errors->all() as $error)
  <p class="pps" style="color:red;">{{ $error }}</p>
  @endforeach
@enderror
<fieldset style="font-family: Open Sans Condensed;" class='row'>
<a href="{{ route('create') }}"><button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:300px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none; display:block;margin:0 auto;">Создать курс</button></a>
<form action="{{ route('addLan') }}" method="POST">
    @csrf
    @method('PUT')
    <p class="pps">Добавить язык</p>
    <input type="text" name="name" style="font-size:14pt;">
    <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none; display:block;margin:0 auto;">Добавить</button>
</form>
</fieldset>
<div class="row">
    <fieldset style="font-family: Open Sans Condensed;">
    <h2>Курсы</h2>
 @foreach ($courses as $one)
          <article class="blog_post">
             <div class="three columns">
            @if(auth() && auth()->user() != null)
            <a href="{{ route ('in', [$one->id, Auth::user()->id]) }}" class="th">
            @else
            <a href="{{ route ('in', $one->id) }}" class="th">
            @endif
             @if($one->image)
             <img src="{{ asset ('storage/' . $one->image) }}">
             @else
             <img src="{{ asset ('storage/images/default.png') }}">
             @endif
             </a>
             </div>
             <div class="nine columns">
              @if(auth() && auth()->user() != null)
              <a href="{{ route ('in', [$one->id, Auth::user()->id]) }}"><h4>{{ $one->title}}</h4></a>
              @else
              <a href="{{ route ('in', $one->id) }}"><h4>{{ $one->title}}</h4></a>
              @endif
              <p>{{ $one->lid }}</p>
              @auth
              @if(auth()->user()->admin === 1)
              <form action="{{ route('delete', $one->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
              <button class="button" style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#e57373; color:#FFFFFF; border:none;">Удалить</button>
              </form>
              @endif
              @endauth
              </div>
          </article>
        @endforeach
    </fieldset>
    <fieldset style="font-family: Open Sans Condensed;">
        <h2>Записи</h2>
        @foreach ($list as $item)
            <article class="blog_post">
                <div class="three columns">
                    <p class="pps">Клиент: {{ $item->user ? $item->user->name : 'Не найден' }}</p>
                    <p class="pps">Курс: {{ $item->course ? $item->course->title : 'Не найден' }}</p>
                    <img src="{{ asset ('storage/' . $item->course->image) }}">
                    <form action="{{ route('deleteCourseUser', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="button" style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:160px; height:40px; background-color:#e57373; color:#FFFFFF; border:none; display:block; margin:0 auto;">Удалить запись</button>
                    </form>
                </div>
            </article>
        @endforeach
    </fieldset>
</div>
@endsection