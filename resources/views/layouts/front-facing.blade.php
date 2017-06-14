<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    <section class="hero-unit container">
        <div class="">
            <h1 class="title is-1">
                <img src="images/geckode-logo-small-white.png" style="width: 100px;">
                <span>GEC<span style="color: #4ee559">KODE</span></span>
            </h1>

            <div class="menu">
                <div class="menu-item"><a href="/client-area">Client area</a></div>
                <div class="menu-item"><a href="/contact-us">Contact us</a></div>
            </div>
        </div>
    </section>

    <div id="app">
        @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    @yield('inline-script')
    <script src="/js/app.js"></script>
</body>
</html>
