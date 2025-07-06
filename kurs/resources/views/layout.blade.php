<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />

  <title>Языковая школа LINGVO</title>

  <link rel="stylesheet" href="{{ asset('stylesheets/foundation.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stylesheets/main.css') }}">

  <script src="{{ asset('js/javascripts/modernizr.foundation.js') }}"></script>
  
  <link rel="stylesheet" href="{{ asset('fonts/ligature.css') }}">

  <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Playfair+Display:400italic' rel='stylesheet' type='text/css' />

</head>
<body>

     <div class="twelve columns header_nav">
     <div class="row">
      
        <ul id="menu-header" class="nav-bar horizontal">
        <div style="position:absolute; right: 24px; top: 20px;">
        @auth
            @if(Auth::user()->admin !== 1)
            <a href="{{ route('lk', ['login' => Auth::user()->login]) }}">
              <img src ="{{ asset('storage/' . Auth::user()->image) }}" style="position:absolute; top:-10px; left:-70px; width:50px; height:50px;">
              <span class="pps" style="margin-right:10px;">{{ Auth::user()->name }}</span>
            </a>
            @else
            <a href="{{ route('admin') }}">
              <img src ="{{ asset('storage/' . Auth::user()->image) }}" style="position:absolute; top:-10px; left:-70px; width:50px; height:50px;">
              <span class="pps" style="margin-right:10px;">{{ Auth::user()->name }}</span>
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none;">
                    Выйти
                </button>
            </form>
        @else
            <a href="{{ route('register') }}"><button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none;">Регистрация</button></a>
            <a href="{{ route('login') }}"><button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#7cd9c3; color:#FFFFFF; border:none;">Войти</button></a>
        @endauth
        </div>
          <div>
            <p class="pps" style="position:absolute; left:32px;">LINGVO</p>
            <img src="{{ asset('storage/images/logo.png') }}" style="position:absolute; width:74px; left:132px; top:-4px;">
          </div>
          <li><a href="{{ route('welcome') }}">Главная</a></li>
          @if(isset($rubrics))
            @foreach($rubrics as $one)
              <li><a href="{{ route('filtered', $one->id) }}">{{ $one->name }}</a></li>
            @endforeach
            @auth
            @if(auth()->user()->admin === 1)
              <li><a href="{{ route('admin') }}">Админ панель</a></li>
            @endif
            @endauth
          @endif
        </ul>
        

        
      </div>  
      </div>
    

@yield ('content')
<footer style="background-color:#F3F3F3;">

      <div class="row">
      
          <div class="twelve columns footer">
              <a href="" class="lsf-icon" style="font-size:16px; margin-right:15px" title="twitter">Twitter</a> 
              <a href="" class="lsf-icon" style="font-size:16px; margin-right:15px" title="facebook">Facebook</a>
              <a href="" class="lsf-icon" style="font-size:16px; margin-right:15px" title="pinterest">Pinterest</a>
              <a href="" class="lsf-icon" style="font-size:16px" title="instagram">Instagram</a>
          </div>
          
      </div>

</footer>
    <script src="{{ asset ('js/javascripts/foundation.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset ('js/javascripts/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset ('js/javascripts/test.js') }}" type="text/javascript"></script>
</body>
</html>