<!DOCTYPE html>
<html>
<head>
    @yield('markup')
</head>
<body style="color: #333; font-family: sans-serif; line-height: 1.35em">
    <div style="border: 1px solid #ededed; border-radius: 8px; margin: 0 auto; max-width: 700px; overflow: hidden;">

        <div style="text-align: center">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARoAAABZAQMAAADbxnmPAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAABlBMVEX///8AAABVwtN+AAAAAWJLR0QB/wIt3gAAAAlwSFlzAAALEgAACxIB0t1+/AAAAH1JREFUSMft0rENgCAQBdAzFJaMwCiMBqM5CiNYUhjPD4Wh0phcNDH/Fxc4Hg2cCPNtJh02momIfoCiqkpS3QSLChRXh1XxKDm0QyJjhJ6kMgMt7VtQ3Y6GRyOH6oieoP7ipY10R+3WgMYZJzJBIu5+xokMUTzxRYgMEfNuDqvlMdqEkEbqAAAAAElFTkSuQmCC">
        </div>

        <div style="padding: 20px;">
            @yield('content')

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