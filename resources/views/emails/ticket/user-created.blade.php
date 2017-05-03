@extends('emails.layouts.notification')

@section('content')
    <h1 style="margin-top: 0">A support ticket has been created</h1>

    <p>Hello,</p>

    <p>A ticket has been created that needs attention.</p>

    <table style="border: 1px solid #e0e0e0; padding: 2em; width: 100%;">
        <tr>
            <td style="font-weight: bold" style="padding-right: 2em">User:</td>
            <td>{{ $user->name }}</td>
        </tr>
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
        You may view or repy to the ticket here:<br>
        <a href="{{ route('tickets.show', ['ticket' => $ticket]) }}">
            {{ route('tickets.show', ['ticket' => $ticket]) }}
        </a>
    </p>
@endsection