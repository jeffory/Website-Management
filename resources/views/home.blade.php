@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2>Welcome back {{ Auth::user()->name }}!</h2>
    <br>

    <div class="columns">
        <div class="column is-3 box has-text-centered icon-box">
            <a href="{{ url('tickets') }}">
            <i class="icon fa fa-ticket is-big"></i>
            @if(Auth::user()->is_admin)
                <h3>Support Tickets</h3>
            @else
                <h3>My Tickets</h3>
            @endif
            </a>

            <p>
                @if(Auth::user()->is_admin)
                <a href="{{ route('tickets.index') }}">There are {{ $ticket_count }} ticket(s) open</a>
                @else
                <a href="{{ route('tickets.index') }}">You have {{ $ticket_count }} ticket(s) open</a>
                @endif
            </p>
        </div>
        
    </div>
</div>
@endsection
