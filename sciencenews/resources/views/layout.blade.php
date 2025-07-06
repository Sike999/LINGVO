<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />

  <title>Новости науки</title>

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
            <span class="pps" style="margin-right:10px;">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">
                    Выйти
                </button>
            </form>
        @else
            <a href="{{ route('register') }}"><button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Регистрация</button></a>
            <a href="{{ route('login') }}"><button style="border-radius:8px; font-family: Open Sans Condensed; font-size:14pt; width:100px; height:40px; background-color:#2BA6CB; color:#FFFFFF; border:none;">Войти</button></a>
        @endauth
        </div> 
          <li><a href="{{ route('welcome') }}">Главная</a></li>
          @if(isset($nav))
          @foreach($nav as $one)
            <li><a href="{{ route('welcome', $one->cat_id) }}">{{ $one->categories->category }}</a></li>
          @endforeach
          @else
          @endif
        </ul>
        

        
      </div>  
      </div>
    

@yield ('content')
<footer>

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