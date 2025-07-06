@extends('layouts.app')

{{-- @section('title', 'Создать резюме') --}}

@section('header')
<h1>Админ-панель</h1>
<p>Фильтр:</p>
<ul>
  <li><a href="{{route('admin.index')}}?filter=active">Активные</a></li>
  <li><a href="{{route('admin.index')}}?filter=past">Прошедшие</a></li>
  <li><a href="{{route('admin.index')}}?filter=empty">Нет мест</a></li>
  <li><a href="{{route('admin.index')}}">Без фильтра</a></li>
</ul>
@endsection

@section('content')

@if ($header !== '')
    <h3>{{$header}}</h3>
@endif


<section class="eight columns">          
    @foreach ($posts as $post)
    @if ($errors->has($post['id']))
    <div class="alert alert-danger">
        {{ $errors->first($post['id']) }}
    </div>
    <br>
    @endif
    <article class="blog_post">
        <div class="three columns">
        <a href="{{ route('lk.post', ['id' => $post['id']]) }}" class="th"><img src="{{asset('storage/'. $post['image']) }}" alt="desc" /></a>
        </div>
        <div class="nine columns">
         <a href="{{ route('lk.post', ['id' => $post['id']]) }}"><h4>{{$post['title']}}</h4></a>
         <p> {{$post['lid']}}</p>
         @auth
         @if(auth()->user()->isAdmin())
           <div>
            <form action="{{route('delete', ['post_id' => $post['id'], 'rubrics_id' => $post['rubric_id']])}}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit">Удалить</button>
            </form>
          </div>  
         @endif
       @endauth
         </div>
     </article>
    @endforeach         
</section>
    
@auth

      @if(auth()->user()->isAdmin())
      <section class="four columns">
        <H3>  &nbsp; </H3>
        <div class="panel">
          <h3>Админ-панель</h3>

        <ul class="accordion">
          <li class="active">
            <div class="title">
              <a href="{{route('create')}}"><h5>Добавить курс</h5></a>
            </div>
          </li>
          <li class="active">
            <div class="title">
              <a href="{{route('create-rubric')}}"><h5>Добавить рубрику</h5></a>
            </div>
          </li>
        </ul>
          
        </div>
      </section>
      @endif

    @endauth
@endsection

@section('footer')
<section>
   
    <div class="section_dark">
    <div class="row"> 
    
    <h2></h2>
    
        <div class="two columns">
        <img src="{{ asset('images/thumb1.jpg') }}" alt="desc" />
        </div>
        
        <div class="two columns">
        <img src="{{ asset('images/thumb2.jpg') }}" alt="desc" />
        </div>
        
        <div class="two columns">
        <img src="{{ asset('images/thumb3.jpg') }}" alt="desc" />
        </div>
        
        <div class="two columns">
        <img src="{{ asset('images/thumb4.jpg') }}" alt="desc" />
        </div>
        
        <div class="two columns">
        <img src="{{ asset('images/thumb5.jpg') }}" alt="desc" />
        </div>
        
        <div class="two columns">
        <img src="{{ asset('images/thumb6.jpg') }}" alt="desc" />
        </div>
  
    
    </div>
    </div>
    
  </section>
@endsection