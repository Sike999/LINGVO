@extends ('layout')

@section('content')
    <header>
            <div class="row">
               <h4>Начало курса: {{(new DateTime($details['date']))->format('d.m.Y, H:i')}}</h4>
               <h4>Количество свободных мест : {{$details->capacity}}</h4>
    <article>
             <div class="twelve columns">
                 <h1>{{ $details->lid }}</h1>   
             </div>
    </article>
            </div>    
    </header>
      
@error('error')
  @foreach ($errors->all() as $error)
  <p class="pps" style="color:red;">{{ $error }}</p>
  @endforeach
@enderror
<section class="section_light">
    <div class="row">
      @if($details->image)
        <img src="{{ asset('storage/' . $details->image) }}" width=500 text-align=left hspace=30>
      @else
        <img src="{{ asset('storage/images/default.png') }}" width=250 text-align=left hspace=30>
      @endif
      <p class="pps">{{ $details->content }}</p>                   
    </div>
    @auth
    @if ($registrable && !$isAlready && $cancelable)
      <div class="row">
        <fieldset>
        <form action="{{route('sub', $details->id)}}" method="POST"> 
            @csrf
            @method('POST')
            <p class="pps">ФИО</p>
            <input type="text" name="FIO" required>
            <p class="pps">Возраст</p>
            <input type="text" name="age" required>
            <p class="pps">Город</p>
            <input type="text" name="city" required>
            <button type="submit" style="display:block; margin:0 auto; border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#000000; border:none;">Записаться</button>
        </form>
        </fieldset>
      </div>
    @elseif ($isAlready && $cancelable)
            <form action="{{ route('unsub', $courseUserId) }}" method="POST">
                  @csrf
                  @method('DELETE')
            <button class="button" style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#e57373; color:#FFFFFF; border:none; display:block; margin:0 auto;">Отписаться</button>
            </form>
    @endif
    @endauth
</section>
@endsection