<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Rasputin Vive</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet">

  <!-- Styles -->
  <style>
  html, body {
    background-color: #2c2c2c;
    font-family: 'Cinzel Decorative', cursive;
    color: red;
    font-weight: 200;
    background: url("https://i0.wp.com/www.institutomillenium.org.br/wp-content/uploads/2017/11/627c8ee453bf007207be9b5cf4780647.jpeg?fit=1920%2C1080&ssl=1");
    height: 100vh;
    margin: 0;
  }

  .filter {
    background-color: rgba(0, 0, 0, 0.9);
  }

  .full-height {
    height: 100vh;
  }

  .flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
  }

  .position-ref {
    position: relative;
  }

  .top-right {
    position: absolute;
    right: 10px;
    top: 18px;
  }

  .content {
    text-align: center;
  }

  .title {
    font-size: 84px;
    font-color: green;
  }

  .links > a {
    color: #fff;
    padding: 0 25px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .1rem;
    text-decoration: none;
    text-transform: uppercase;
  }

  .m-b-md {
    margin-bottom: 30px;
  }
  </style>
</head>
<body>
  <div class="filter">
    <div class="flex-center position-ref full-height">
      @if (Route::has('login'))
      <div class="top-right links">
        @auth
        <a href="{{ url('/home') }}">Home</a>
        @else
        <a href="{{ route('login') }}">Login</a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}">Register</a>
        @endif
        @endauth
      </div>
      @endif

      <div class="content">
        <div class="title m-b-md">
          Rasputin Project
        </div>

        <div class="links">
          <a href="https://laravel.com/docs">Docs</a>
          <a href="https://laracasts.com">Laracasts</a>
          <a href="https://laravel-news.com">News</a>
          <a href="https://blog.laravel.com">Blog</a>
          <a href="https://nova.laravel.com">Nova</a>
          <a href="https://forge.laravel.com">Forge</a>
          <a href="https://github.com/laravel/laravel">GitHub</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
