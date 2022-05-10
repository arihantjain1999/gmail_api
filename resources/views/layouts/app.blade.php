<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MailApp') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/gmail.css') }}" rel="stylesheet">
    <style>
       
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light sticky-top shadow-sm" id="navbar">
            {{-- <span style="font-size:20px;cursor:pointer" class="m-2 mx-4 text-light" onclick="openNav()">&#9776;</span> --}}
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @if(Auth::check())
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                @php
                                       $usertype = DB::table('users')
                                       ->select('user_type')
                                       ->where('email', Auth::user()->email)
                                       ->first();
                                    //    dd($usertype->user_type);
                                   @endphp
                                  
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- @dd(Auth::user()->email); --}}
                                    <a class="dropdown-item" href="{{ route('label.showUser' , ['email' => Auth::user()->email]) }}">
                                        User Profile
                                    </a>
                                    @if ( $usertype->user_type == 'Admin' ) 
                                    <a class="dropdown-item" href="{{ route('user.index') }}">Admin</a>
                                    
                                    <a class="dropdown-item" href="{{ route('user.create') }}">Register</a>
                                    @endif
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
            {{-- <div class="form-check form-switch" title="Change Mode" onclick="myFunction()">
                <input class="form-check-input mx-4 dark" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
        </div> --}}
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
    <script src="https://kit.fontawesome.com/e6aaff51be.js" crossorigin="anonymous"></script>
</body>
</html>