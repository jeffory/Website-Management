@extends('layouts.app')

@section('content')
<div class="container main-container content">
    <h2><strong>Ticket:</strong> {{ $ticket->title }}</h2>

    <noscript>
    @foreach($ticket->messages as $index => $message)
    <div class="ticket-listing columns">
        <div class="column is-2">
            <p>
                <span>{{ $message->username() }}</span><br>
                <span class="is-small">{{ $message->updated_at->diffForHumans() }}</span>
            </p>
        </div>

        <div class="ticket-message column is-9">
            <p>{{ $message->message }}</p>
        </div>

        <div class="controls column has-text-right">
            {{-- <a class="button" href="#">Edit</a> --}}

            <form role="form" method="POST" action="{{ url('/tickets', [$ticket->id, 'message', $message->id]) }}" style="display: inline">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button class="delete" type="submit" name="delete"></button>
            </form>
        </div>
    </div>
    @endforeach
    </noscript>

    
    <ticket-details :user-id="{{Auth::user()->id}}" :ticket-id="{{$ticket->id}}" inline-template v-cloak>
        <transition appear name="fade">
            <div>
                <div class="columns ticket-listing" v-for="message in ticket.messages">
                    <div class="column is-2">
                        <p>
                            <span>@{{ message.user.name }}</span><br>
                            <timeago :since="Date.parse(message.updated_at)" :auto-update="1"></timeago>
                        </p>
                    </div>
    
                    <div class="ticket-message column is-9">
                        <p>@{{ message.message }}</p>
                    </div>
    
                    <div class="controls column has-text-right">
                        <button class="delete" @click="deleteMessage(message.id)"></button>
                    </div>
                </div>

                <p class="control">
                    <textarea class="textarea" placeholder="New message" v-model="newMessage"></textarea>
                </p>

                <p class="control">
                    <button class="button is-wide is-primary" @click="storeMessage()">Add new message</button>
                </p>

                <p class="control">
                    <button class="button is-wide is-danger" @click="deleteTicket(ticket.id)">Delete Ticket</button>
                </p>
            </div>
        </transition>
    </ticket-details>
</div>
@endsection

@section('inline-script')
<script>
    var ticket = {!! json_encode($ticket) !!};
</script>
@endsection