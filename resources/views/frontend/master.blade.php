<!DOCTYPE html>
<html lang="{{ str_replace('gr','el',app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Survey application">
    <meta name="author" content="Tassos Kolydas, http://www.kolydart.gr">

    <title>{{ config('app.name') }}</title>

    {{-- <link rel="icon" href="/favicon.ico"> --}}
    {{-- <link href="{{ mix('/css/app.css') }}" rel="stylesheet" > --}}
    {{-- <script src="{{ mix('/js/app.js') }}"></script> --}}

    <!-- Bootstrap CSS -->
    <link href="/css/bootstrap.3.3.7.min.css" rel="stylesheet">
    {{-- font-awesome --}}
    <link href="/css/font-awesome.4.4.7.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="/js/jquery-1.12.4.min.js"></script>

    @yield('head','')

    <link rel="stylesheet" href="/css/custom.css"> 
    
  </head>

  <body @if (!Auth::check()) class="bg-dark" @endif>
    <div id="app">

      @yield('menu','')

      <div class="container">

          @include('partials.messages')

          @yield('content')

      </div>  
      
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="/js/bootstrap.3.3.7.min.js"></script>
    @yield('javascript','')
  </body>
</html>
