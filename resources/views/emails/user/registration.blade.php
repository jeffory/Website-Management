@extends('emails.layouts.notification')

@section('content')
    <h1 style="margin-top: 0">Almost there...</h1>

    <p>Hello {{ $user->name }},</p>

    <p>
        Welcome to the support centre.
    </p>

    <p>
        Please verify your email using the link below:<br>

        <a href="{{ route('user.verify', ['token' => $user->verification_token]) }}">
            {{ route('user.verify', ['token' => $user->verification_token]) }}
        </a>
    </p>
@endsection

@section('markup')
    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "EmailMessage",
          "potentialAction": {
            "@type": "SaveAction",
            "name": "Verify Account",
            "handler": {
              "@type": "HttpActionHandler",
              "url": "{{ route('user.verify', ['token' => $user->verification_token]) }}"
            }
          },
          "description": "Verify your new account."
        }
    </script>
@endsection