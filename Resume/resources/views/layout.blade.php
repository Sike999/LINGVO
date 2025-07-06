<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ asset('resume.css') }}" rel="stylesheet">
</head>
<body>
@if(session('success'))
    {{ session('success') }}
@endif

@if(session('error'))
    {{ session('error') }}
@endif
<div class="header"><!--*****************Логотип и шапка********************-->
Резюме и вакансии
<div id="logo"></div>
	</div>
    <div class="container">      
<div class="rightcol"><!--*******************Навигационное меню*******************-->
  <ul class="menu">
     	<li><a href="/">Вакансии</a></li>
        <li><a href="{{ route ('create')}}">Добавить новое резюме</a></li>
        <li><a href="/firstQuery">Фамилии персон, имеющих стаж от 5 до 15 лет</a></li>
    	<li><a href="/secondQuery">Фамилии и стаж людей с профессией Программист</a></li>
    	<li><a href="/thirdQuery">Общее число резюме в базе</a></li>
     	<li><a href="/fourthQuery">Профессии, представители которых имеются в резюме</a></li>
   </ul>
</div>
<div class="leftcol">
        @yield('content')
</div>
</div>
<div class="footer">&copy; Copyright 2017</div>

</body>
</html>