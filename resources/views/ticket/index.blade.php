@extends('layouts.client-area')

@section('content')
<div class="container main-container" id="tickets-management">
    <h2>My tickets</h2>

    @if (! $user->isVerified())
    <article class="message is-warning">
        <div class="message-body">
            Please verify your email to enable support ticket creation. If you have not recieved the email, you may <a href="{{ route('user.sendVerification') }}">resend it</a>.
        </div>
    </article>
    @endif

    @if ($tickets->count() == 0)
    <p style="font-size: 13pt; padding: 1em 0; text-align: center">
        No active support tickets found.<br>
        <i class="fa fa-smile-o"></i>
    </p>
    @else
    <table class="table" style="table-layout:auto">
        <thead>
            <tr>
                <th>Status</th>
                <th>Title</th>
                <th>Last update</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $index => $ticket)
                <tr>
                    <td style="width: 15%">
                        @if ($ticket->trashed())
                        <span class="tag is-danger">Trashed</span>
                        @endif

                        @if ($ticket->isOpen())
                        <span class="tag is-info">Open</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('tickets.show', ['ticket' => $ticket]) }}">{{ $ticket->title }}</a>
                    </td>

                    <td style="width: 20%">{{ $ticket->updated_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tickets->links('partials.bulma-pagination') }}
    @endif

    <div class="column">

        <a class="button is-primary {{ (!$user->isVerified()) ? 'is-disabled': '' }}" href="{{ route('tickets.create') }}">
            <span class="icon is-small">
                <i class="fa fa-plus"></i>
            </span>
            <span>Create new ticket</span>
        </a>

    </div>
</div>
@endsection