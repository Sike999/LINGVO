<!DOCTYPE html>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>@yield('title', 'Языковая школа LINGVO')</title>
  
  <!-- Included CSS Files (Uncompressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.css">
  -->
  
  <!-- Included CSS Files (Compressed) -->
  <link rel="stylesheet" href="{{ asset('stylesheets/foundation.min.css') }}">
       <link rel="stylesheet" href="{{ asset('stylesheets/main.css') }}">
  <link rel="stylesheet" href="{{ asset('stylesheets/app.css') }}">

  <script src="{{ asset('javascripts/modernizr.foundation.js') }}"></script>
  
  <link rel="stylesheet" href="{{ asset('fonts/ligature.css') }}">
  
  <!-- Google fonts -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Playfair+Display:400italic' rel='stylesheet' type='text/css' />

  <!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>

<body>

<!-- ######################## Main Menu ######################## -->
 
<nav>

     <div class="twelve columns header_nav">
     <div class="row">
        <ul id="menu-header" class="nav-bar horizontal">
        
          <li><a href="{{ route('home') }}">Главная</a></li>
          @foreach ($rubrics as $rubric)
          <li><a href="{{route('rubrika', ['rubrics_id' => $rubric['id']])}}">{{$rubric['name']}}</a></li>  
          @endforeach

          @guest
          <li><a href="{{ route('login') }}">Вход</a></li>
          <li><a href="{{ route('registration') }}">Регистрация</a></li>
          @endguest

          @auth
          @if (auth()->user()->isAdmin())
            <li><a href="{{ route('admin.index') }}">Админ-панель</a></li>  
          @endif
            <li><a href="{{ route('lk.index') }}">Личный кабинет</a></li>
            <li><a href="{{ route('logout') }}">Выйти</a></li>
          @endauth
        </ul>
      </div>  
      </div>
      
</nav>
<!-- END main menu -->  
      
<header>
  <div class="row">
    @yield('header')
  </div>
</header>

<!-- ######################## Section ######################## -->

<section class="section_light">
  <div class="row">
    @yield('content')
  </div>
</section>


@yield('footer')

<!-- ######################## Footer ######################## -->  
      
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

<!-- ######################## Scripts ######################## --> 

    <!-- Included JS Files (Compressed) -->
    <script src="{{ asset('javascripts/foundation.min.js') }}" type="text/javascript"></script> 
    <!-- Initialize JS Plugins -->
     <script src="{{ asset('javascripts/app.js') }}" type="text/javascript"></script>
</body>
</html>