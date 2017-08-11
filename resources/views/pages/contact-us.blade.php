@extends('layouts.front-facing')

@section('content')
   <section class="content">

        <h2>Contact us</h2>

        <div class="columns">
            <div class="column">
                @if(session()->has('contact_success'))
                    <div class="message is-success">
                        <div class="message-body">
                            Message sent successfully.
                        </div>
                    </div>
                @else
                    <form action="{{ route('contact_us') }}" method="post" id="contact-form">
                        {{ csrf_field() }}

                        <p class="field">
                            <div class="field-label">
                                <label class="label" for="name">Name:</label>
                            </div>
                            <div class="field-body">
                                <input class="input" type="text" name="name" value="{{ old('name') }}" required>
                                @if ($errors->has('name'))
                                    <p class="help is-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                        </p>

                        <p class="field">
                            <div class="field-label">
                                <label class="label" for="email">Email:</label>
                            </div>
                            <div class="field-body">
                                <input class="input" type="text" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <p class="help is-danger">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                        </p>

                        <p class="field">
                            <div class="field-label">
                                <label class="label" for="name">Message:</label>
                            </div>
                            <div class="field-body">
                                <textarea class="textarea" name="body" required>{{ old('body') }}</textarea>

                                @if ($errors->has('body'))
                                    <p class="help is-danger">{{ $errors->first('body') }}</p>
                                @endif
                            </div>
                        </p>

                        @if ($errors->has('g-recaptcha-response'))
                            <p class="help is-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                        @endif

                        <p class="field">
                            @if (!env('APP_ENV') == 'testing')
                                <button type="submit" class="button is-primary is-medium g-recaptcha"
                                        data-sitekey="6LeTRyYUAAAAABV1QjQj272RF0_rz4ApDw_X4vdD"
                                        data-callback="submitMessageForm">Submit</button>
                            @else
                                <button type="submit" class="button is-primary is-medium">Submit</button>
                            @endif
                        </p>
                    </form>
                @endif
            </div>

            <div class="column">
                <p class="highlight">
                    Alternatively if you do not want to use this form, you can email general enquiries to
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMMAAAARCAMAAABXV+i8AAAAG1BMVEX///8AAAA/Pz8fHx9fX1/f39+fn5+/v79/f38RWZoXAAABa0lEQVR4Ae2V0Y7sMAhDqTGQ///iq9IWWVVe5mnvSGNpKwo5zpKgjn2Jfgr4xAXwM+Kd/yvhCQJmxU+Il/D3PTDm7T/rAU4zYz8DAOJ8hdsC6t756Co6fw8VymFmCSDtyS2aEuPXVQZsCNWwQtCJ5HvlmUHdY5kym7Ov1UnHtamj5vTIJ8hr3floh1xmturJlQuhfl0llBANK4SXee6uLjo1j0uw8KsTRC+iTdzR4h2cEJ5a+LgIq8T47YnRsELMVqZKAPBND4xkVU7uXXdOD6wxvp6O1uSUEL8NocI7wr6HxdB/T4qZNPbtUu9BfJN3EISHnir1LN+E+HFDjITl/h60w20PwfWe3/fZrC6zdH7pZ7RmnDntDzF+G6LlR5iwQkwPuq6LnFlabiMvS8z3oVsADoDzy8QDdnvHfJfwROxcdiSE+nW1HCHE811QdggeNKf136wLtMvjnB19qAblfr5eSftuEaD99JPoH5dsB9Khhx/HAAAAAElFTkSuQmCC" style="margin: .2em 0 -.3em 0">.
                </p>
            </div>
        </div>
    </section>
@endsection