<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="nav">
            <div class="container">
                <div class="nav-left">
                    <a class="nav-item nav-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                </div>

                <div class="nav-right">
                    @if (Auth::guest())
                        <a class="nav-item" href="{{ url('/login') }}">Login</a>
                        <a class="nav-item" href="{{ url('/register') }}">Register</a>
                    @else
                    <a class="nav-item" href="{{ url('/tickets') }}">My tickets</a>

                    
                    <a class="nav-item" href="{{ url('/logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    @endif
                </div>
            </div>
        </nav>

        @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    {{-- <script src="https://npmcdn.com/vue-timeago@2.1.2/index.umd.js"></script> --}}
    @yield('inline-script')
    <script src="/js/app.js"></script>

    {{-- <script src="http://livejs.com/live.js"></script> --}}
</body>
</html>
