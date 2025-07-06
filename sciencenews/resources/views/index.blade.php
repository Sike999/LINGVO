@extends ('layout')

@section('content')
<header>
   

        <div class="row">
        
        <h1>Новости науки</h1>
        
</header>
<section>

  <div class="section_main">
   
      <div class="row">
      
          <section class="eight columns">          
        @foreach ($news as $one)
          <article class="blog_post">
          
             <div class="three columns">
             <a href="{{ route ('in', $one->id) }}" class="th">
             @if($one->img)
             <img src="{{ asset ('storage/' . $one->img) }}">
             @else
             <img src="{{ asset ('storage/images/default.png') }}">
             @endif
             </a>
             </div>
             <div class="nine columns">
              <a href="{{ route ('in', $one->id) }}"><h4>{{ $one->link}}</h4></a>
              <p>{{ $one->head }}</p>
              @auth
              @if(auth()->user()->is_admin == TRUE)
              <form action="{{ route('delete', $one->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
              <button class="button" style="border-radius:8px; font-family: Open Sans Condensed; font-style:normal; font-size:12pt; border:none;">Удалить</button>
              </form>
              @endif
              @endauth
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

   <section>
   
      <div class="section_dark">
      <div class="row"> 
      
      <h2></h2>
      @foreach($imgs as $img)
          <div class="two columns">
            @if($img->img)
             <a href="{{ route ('in', $img->id) }}"><img src="{{ asset ('storage/' . $img->img) }}"></a>
             @else
             <a href="{{ route ('in', $img->id) }}"><img src="{{ asset ('storage/images/default.png') }}" style="width:92px"></a>
             @endif
          </div>
      @endforeach
      </div>
      </div>
      
    </section>
@endsection