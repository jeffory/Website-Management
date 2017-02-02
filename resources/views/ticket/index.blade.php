@extends('layouts.app')

@section('content')
<div class="container main-container content" id="tickets-management">
    <h2>My tickets</h2>

    @if (! $user->is_verified)
        <p>Please verify your email to post tickets.</p>
    @endif

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

    <div class="column">
        <a class="button is-primary" href="{{ route('tickets.create') }}">
            <span class="icon is-small">
                <i class="icon fa fa-plus"></i>
            </span>
            <span>Create new ticket</span>
        </a>
    </div>
</div>
@endsection