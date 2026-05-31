<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    @yield('head')
</head>


<body class="hold-transition skin-purple sidebar-mini">

<div id="wrapper">

@include('partials.topbar')
@include('partials.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            @include('partials.messages')
            @if(isset($siteTitle))
                <h3 class="page-title">
                    {{ $siteTitle }}
                </h3>
            @endif

            <div class="row">
                <div class="col-md-12">

                    @if (Session::has('message'))
                        <div class="alert alert-info">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    @if ($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                    <div class="row text-right" style="margin-right:20px;">
                        <x-kolydart::signature />
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<form action="{{ route('auth.logout') }}" method="POST" style="display:none;" id="logout">
    @csrf
    <button type="submit">Logout</button>
</form>

@include('partials.javascripts')
@yield('javascript')

</body>
</html>