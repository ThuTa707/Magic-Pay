<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Date Range Picker --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    {{-- Customize --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/mystyle.css') }}">

    @yield('style')
</head>

<body>


    @guest


        @yield('content')


    @else

        <div id="app">
            {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
    
                        </ul>
    
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>
    
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
    
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav> --}}


            <div class="header-menu">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="row">
                            <div class="col-2 text-center my-2">

                                @if (Request::url() != 'http://127.0.0.1:8000')
                                    <a class="back-btn">
                                        <i class="fa fa-angle-left align-middle" aria-hidden="true"></i>
                                    </a>
                                @endif

                            </div>
                            <div class="col-8 text-center my-2">
                                <a href="">
                                    <h4 class="mb-0">
                                        @yield('headerBar_title') </h4>
                                </a>


                            </div>
                            <div class="col-2 text-center my-2">
                                <a href="{{route('notifications')}}">
                                    <i class="fa fa-bell align-middle" aria-hidden="true"></i>
                                    @if($unread_noti_count != 0)
                                    <span class="badge badge-pill badge-danger unread_noti_count">{{$unread_noti_count}}</span>
                                    @endif
                                
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="content">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>



            <div class="bottom-menu">


                <div class="scan-tab">
                    <div class="inner">
                        <a href="">
                            <i class="fa fa-qrcode" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="row">

                            <a href="{{ route('home') }}" class="col-3 text-center my-2">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <p class="mb-0">Home</p>
                            </a>

                            <a href="{{route('wallet')}}" class="col-3 text-center my-2">
                                <i class="fa fa-wallet" aria-hidden="true"></i>
                                <p class="mb-0">Wallet</p>
                            </a>

                            <a href="{{route('transaction')}}" class="col-3 text-center my-2">
                                <i class="fa fa-exchange-alt" aria-hidden="true"></i>
                                <p class="mb-0">Transcations</p>
                            </a>

                            <a href="{{ route('profile') }}" class="col-3 text-center my-2">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <p class="mb-0">Account</p>
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endguest





    {{-- Bootstrap --}}
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- Sweet Alert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Jscroll Infinity --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}
    <script src="{{asset('frontend/js/jscroll.min.js')}}"></script>

    
    {{-- Date Range Picker --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    @include('frontend.layouts.session')

    @yield('foot')

    <script>
        $(".back-btn").click(function(e) {
            e.preventDefault();

            window.history.go(-1);
            return false;
        })
    </script>

</body>

</html>
