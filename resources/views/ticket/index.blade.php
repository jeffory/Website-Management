@extends('layouts.app')

@section('content')
<div class="container main-container content" id="tickets-management">
    <div class="columns">

    <div class="column">
        <h2>My tickets</h2>
    </div>

    <div class="column has-text-right">
        <a class="button is-primary" href="{{ url('/tickets/create') }}">
                <span class="icon is-small">
                    <i class="icon fa fa-plus"></i>
                </span>
                <span>Create new ticket</span>
            </a>
        </div>
    </div>

    <table class="table">
    @foreach($tickets as $index => $ticket)
        <tr>
            <td><a href="{{ url('tickets', [$ticket->id]) }}">{{ $ticket->title }}</a></td>
            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
        </tr>
    @endforeach
    </table>
</div>
@endsection