@inject('flash', '\App\Helpers\FlashMessage')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Client Area | {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ url('/css/app.css') }}" rel="stylesheet" type="text/css">

    @if (isset($pdf_mode) && $pdf_mode)
    <link href="{{ url('/css/invoice-pdf-fixes.css') }}" rel="stylesheet" type="text/css">
    @endif

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'pusherKey' => env('PUSHER_KEY')
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        {{ $flash->displayBulma() }}

        <div class="page-container">

            <div class="nav-sidebar is-desktop is-hidden-print">
                <div class="has-text-centered">
                    <img src="/images/geckode-logo-small-white.png" style="max-width: 100px;" class="logo">

                    <h1 class="title is-2">
                        GEC<span class="alt-color">KODE</span>
                    </h1>
                </div>

                @include('layouts.nav-desktop')
            </div>

            @include('layouts.nav-small')

            <section class="page-content">
                 @yield('content')
            </section>
        </div>
    </div>

    <!-- Scripts -->
    @yield('pre-inline-script')

    {{-- <script src="https://cdn.polyfill.io/v2/polyfill.js"></script> --}}
    <script src="/js/app.js"></script>

    @yield('inline-script')
</body>
</html>
