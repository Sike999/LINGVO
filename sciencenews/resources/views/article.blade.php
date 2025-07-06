@extends ('layout')

@section('content')
    <header>
            <div class="row">
               <a href="{{ route ('welcome',$news->cat_id) }}"><h4>{{ $news->categories->category }}</h4></a>
    <article>
             <div class="twelve columns">
                 <h1>{{ $news->link }}</h1>
                      <p class="excerpt">
                          {{ $news->head }}
                      </p>    
             </div>
    </article>
            </div>    
    </header>
      

<section class="section_light">     
      <div class="row">
      @if($news->img)
      <img src="{{ asset('storage/' . $news->img) }}" width=500 text-align=left hspace=30>
      @else
      <img src="{{ asset('storage/images/default.png') }}" width=250 text-align=left hspace=30>
      @endif
      <p>{{ $news->text }}</p>                   
      </div>
</section>
@endsection