<!DOCTYPE html>
<html>
<head>
    <title>{{ $title or '' }} | {{ config('app.name', '') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('markup')
</head>
<body style="color: #333; font-family: sans-serif; line-height: 1.35em">
    <div style="border: 1px solid #ededed; border-radius: 8px; margin: 0 auto; max-width: 700px; overflow: hidden;">
        <div style="background-color: #636363; color: #fff; font-size: 1.5em; margin-bottom: .3em; padding: .9em">
            {{ config('app.name', '') }}
        </div>

        <div style="padding: 20px;">
            @yield('content')

            <br>
            <p style="margin-bottom: 1.5em">
                Warm regards,<br>
                Keith McGahey
            </p>

            <p style="font-size: .8em; font-style: italic; text-align: center;">
                Please use the ticketing website to submit replies. <br>
                Replies to this email are not monitored.
            </p>
        </div>
    </div>
</body>
</html>