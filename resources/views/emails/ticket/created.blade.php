@extends('emails.layouts.notification')

@section('content')
    <h1 style="margin-top: 0">Your support ticket has been received</h1>

    <p>Hello {{ $ticket->user->name }},</p>

    <p>This email is automated message to confirm the receipt of your support ticket.</p>

    <table style="border: 1px solid #e0e0e0; padding: 2em; width: 100%;">
        <tr>
            <td style="font-weight: bold" style="padding-right: 2em">Subject:</td>
            <td>{{ $ticket->title }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold" style="padding-right: 2em">Status:</td>
            <td>Open</td>
        </tr>
    </table>

    <p>
        You may view or update the ticket here:<br>
        <a href="{{ route('tickets.show', ['ticket' => $ticket]) }}">
            {{ route('tickets.show', ['ticket' => $ticket]) }}
        </a>
    </p>

    <p>
        We endeavour to reply to your support ticket within the next day or two.
    </p>
@endsection