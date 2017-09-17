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

    @if (Route::currentRouteName() == 'invoice.generate_pdf')
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

    @yield('content')
</div>

<!-- Scripts -->
@yield('pre-inline-script')

{{-- <script src="https://cdn.polyfill.io/v2/polyfill.js"></script> --}}
<script src="/js/app.js"></script>

@yield('inline-script')
</body>
</html>
