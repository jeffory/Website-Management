@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2>Welcome back {{ $user->name }}!</h2>
    <br>

    <div class="columns">
        <div class="column is-3 box has-text-centered icon-box">
            <a href="{{ route('tickets.index') }}">
            <i class="icon fa fa-ticket is-big"></i>
            @if($user->isAdmin())
                <h3>Support Tickets</h3>
            @else
                <h3>My Tickets</h3>
            @endif
            </a>

            <p>
                @if($user->isAdmin())
                <a href="{{ route('tickets.index') }}">There are {{ $ticket_count }} ticket(s) open</a>
                @else
                <a href="{{ route('tickets.index') }}">You have {{ $ticket_count }} ticket(s) open</a>
                @endif
            </p>
        </div>

        @if($user->hasServerAccess())
        <div class="column is-3 box has-text-centered icon-box" style="margin-left: 1em">
            <a href="{{ route('server.index') }}">
                <i class="icon fa fa-server is-big"></i>
                <h3>Website management</h3>
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
