@extends('layouts.client-area')

@section('content')
<div class="container main-container">

    <nav class="level">
        <div class="level-left">
            <div class="level-item">
                <h2 class="is-marginless"><strong>Ticket:</strong> {{ $ticket->title }}</h2>
            </div>
        </div>

        <div class="level-right">
            <div class="level-item">

                <dropdown-menu v-cloak alignment="right">

                    <template slot="button" class="test1">
                        <span class="icon">
                            <i class="fa fa-bars"></i>
                        </span>
                    </template>

                    <template slot="menu">
                        <ul class="menu-list">
                            <li><a>Share with user</a></li>
                            <li><a>Delete Ticket</a></li>
                        </ul>
                    </template>
                </dropdown-menu>

            </div>
        </div>
    </nav>
    
    <ticket-details user-id="{{Auth::user()->id}}" user-name="{{Auth::user()->name}}" :data="ticket" inline-template v-cloak>
        <div v-if="ticket">
             <div class="columns ticket-listing" v-for="message in ticket.messages">
                <div class="column is-2">
                    <p class="content">
                        <span>@{{ message.user.name }}</span><br>
                        <timeago :since="Date.parse(message.updated_at)" :auto-update="1"></timeago>
                    </p>
                </div>

                <div class="ticket-message column is-9">
                    <p class="content">@{{ message.message }}</p>
                </div>

                <div class="controls column has-text-right">
                    <button class="delete" @click="deleteMessage(message.id)"></button>
                </div>
            </div>

            <div class="viewers-listing">
                Viewers:&nbsp;&nbsp;&nbsp;
                <span class="tag is-medium" v-for="viewer in viewers" style="margin-right: 3px">@{{ viewer.name }}</span>
            </div>
            <br>

            <p class="control">
                <textarea class="textarea" placeholder="New message" v-model="newMessage" :disabled="isPosting" name="message" style="min-height: 150px"></textarea>
            </p>

            <p class="control">
                <button class="button is-wide is-primary" @click="storeMessage()" :disabled="isPosting">Add new message</button>
            </p>

            <p class="control">
                <button class="button is-wide is-danger" @click="deleteTicket(ticket.id)">Delete Ticket</button>
            </p>
        </div>
    </ticket-details>
</div>
@endsection

@section('inline-script')
<script>
    var ticket = {!! json_encode($ticket) !!};
</script>
@endsection