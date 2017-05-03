@extends('emails.layouts.notification')

@section('content')
    <h1 style="margin-top: 0">Almost there...</h1>

    <p>Hello {{ $user->name }},</p>

    <p>
        Welcome to the Geckode support centre.
    </p>

    <p>
        Please click the button below to verify your email address:
    </p>

    <p style="padding: 1.5em 0;">
        <a href="{{ route('user.verify', ['token' => $user->verification_token]) }}" style="background-color: #21a8bd; color: #fff; padding: 1em 3em; text-decoration: none; border-radius: 4px">
            Verify Email
        </a>
    </p>
@endsection

@section('markup')
    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "EmailMessage",
          "description": "Verify your new account.",
          "potentialAction": {
            "@type": "ConfirmAction",
            "name": "Verify Account",
            "handler": {
              "@type": "HttpActionHandler",
              "url": "{{ route('user.verify', ['token' => $user->verification_token]) }}"
            }
          }
        }
    </script>
@endsection