<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>{{ Config::get('app.name', 'questionnaire') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/blog.css" rel="stylesheet">
  </head>

  <body>

    {{-- @include('layouts.nav') --}}

    {{-- @include('layouts.header') --}}
    
    @include('layout.messages')

    <div class="container">
      <div class="row">
        
        {{-- <div class="col-sm-8 blog-main"> --}}
            @yield('content')
        {{-- </div> --}}

        {{-- @include('posts.sidebar') --}}
      </div>
    </div>  
    
    {{-- @include('layouts.footer') --}}

    {{-- @include('layouts.bottomjs') --}}

  </body>
</html>

