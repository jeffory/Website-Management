@extends('emails.layouts.notification')

@section('content')
    <h1 style="line-height: 1.2em;">You have received a new message from the contact form</h1>

    <p>
        {{ $body }}
    </p>
@endsection
