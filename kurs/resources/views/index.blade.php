@extends ('layout')

@section('content')
<header>
  <div class="row">      
</header>
<section>

  <div class="section_main">
    <div class='frfr' style='margin-left:500px;'>
      <p>Фильтр:</p>
      <ul>
        <li><a href="{{route('welcome')}}?filter=active">Активные</a></li>
        <li><a href="{{route('welcome')}}?filter=past">Прошедшие</a></li>
        <li><a href="{{route('welcome')}}?filter=empty">Нет мест</a></li>
        <li><a href="{{route('welcome')}}">Сбросить</a></li>
      </ul>
  </div>
      <div class="row">
          @if(isset($head))
          <h2>{{ $head }}</h2>
          @endif
          <section class="eight columns">          
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
              </div>
          </article>
        @endforeach
        @auth
        @if(auth()->user()->is_admin == TRUE)
        <fieldset style="font-family: Open Sans Condensed; position:absolute; left:740px; top:-34px;">
            <a href="{{ route ('create') }}" class="link" style="margin-left:40px; margin-right:40px;"><button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:300px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Новый пост</button></a> 
            <form action="{{ route('addCat') }}" method="POST">
              @csrf
              @method('PUT')
              <p class="pps">Добавить категорию</p>
              <input type="text" name="cat">
              <button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Добавить</button>
            </form>
        </fieldset>
        @endif
        @endauth
          </section>
      </div>
      
    </div>
      
</section>
@endsection