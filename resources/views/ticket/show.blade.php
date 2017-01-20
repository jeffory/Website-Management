@extends('layouts.app')

@section('content')
<div class="container main-container content">
    <h2><strong>Ticket:</strong> {{ $ticket->title }}</h2>

    
    @foreach($ticket->messages as $index => $message)
    <div class="ticket-listing columns">
        <div class="column is-2">
            <p>
                {{ $message->username() }}<br>
                <span class="is-small">{{ $message->updated_at->diffForHumans() }}</span>
            </p>
        </div>

        <div class="ticket-message column is-9">
            <p>
                {{ $message->message }}
            </p>
        </div>

        <div class="controls column">
            {{-- <a class="button" href="#">Edit</a> --}}

            <form role="form" method="POST" action="{{ url('/tickets', [$ticket->id, 'message', $message->id]) }}" data-behavior="turbolinks-form" style="display: inline">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <input class="button is-danger" type="submit" name="delete" value="Delete">
            </form>
        </div>
    </div>
    @endforeach
    

    <form role="form" method="POST" action="{{ url('/tickets', [$ticket->id, 'message']) }}">
        {{ csrf_field() }}

        <p class="control">
            <textarea class="textarea" name="message" placeholder="Message"></textarea>
        </p>

        <p class="control">
            <input class="button is-wide is-primary" type="submit" name="save" value="Add to conversation">
        </p>
    </form>

    <form role="form" method="POST" action="{{ url('/tickets', [$ticket->id]) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <p class="control">
            <input class="button is-wide is-danger" type="submit" name="delete" value="Delete Ticket">
        </p>
    </form>
</div>
@endsection