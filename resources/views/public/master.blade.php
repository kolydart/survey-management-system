<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

    @yield('head','')
  </head>

  <body @if (!Auth::check()) class="bg-dark" @endif>
    <div id="app">

      @yield('menu','')

      @include('partials.messages')

      <div class="container">
          @yield('content')
      </div>  
      
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  </body>
</html>
