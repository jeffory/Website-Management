@extends('layouts.client-area')

@section('content')
@include('partials/modal/delete-ticket-modal')
<div class="container main-container">
    <ticket-details user-id="{{$user->id}}" username="{{$user->name}}" :data="ticket" ref="ticket-details" inline-template v-cloak>
        <div>
            <nav class="level">
                <div class="level-left">
                    <div class="level-item">
                        <h2 class="is-marginless">
                            <span v-if="ticket.status == 1" class="tag is-large is-danger" style="margin-top: .1em; margin-right: .4em">Closed</span>
                            <strong>{{ $ticket->title }}</strong>
                        </h2>
                    </div>
                </div>
            
                <div class="level-right">
                    <div class="level-item">
                        <dropdown-menu v-cloak alignment="right">
                            <template slot="button" class="test1">
                                Options
                            </template>
            
                            <template slot="menu">
                                <ul class="menu-list">
                                    <li>
                                        <a @click="eventbus.$emit('show-modal', {id: 'delete-ticket-modal'})">Delete Ticket</a>
                                    </li>
                                </ul>
                            </template>
                        </dropdown-menu>
                    </div>
                </div>
            </nav>
                
            <div v-if="ticket">
                <div style="clear: both; margin-bottom: 1em">
                     <div class="columns ticket-listing" v-for="message in ticket.messages">
                        <div class="column is-2">
                            <p class="content">
                                <span>@{{ message.user.name }}</span><br>
                                <timeago :since="Date.parse(message.updated_at)" :auto-update="1"></timeago>
                            </p>
                        </div>
                
                        <div class="ticket-message column is-9">
                            <p class="content" v-if="message.message">
                                @{{ message.message }}
                            </p>

                            <div class="message is-info" v-if="message.status_change !== null && parseInt(message.status_change) == 1">
                                <p class="message-body">
                                    Ticket was closed.
                                </p>
                            </div>

                            <div class="message is-info" v-if="message.status_change !== null && parseInt(message.status_change) == 0">
                                <p class="message-body">
                                    Ticket was reopened.
                                </p>
                            </div>
                
                            <div v-if="message.file.length > 0">
                                <ul style="display: inline-block;">
                                    <li v-for="file in message.file" style="background-color: hsl(271, 100%, 71%); margin: 3px;" class="tag is-medium">
                                        <a :href="file.url" style="color: #fff" :title="file.file_size">@{{ file.name }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                
                        <div class="controls column has-text-right">
                            <button class="delete" @click="deleteMessage(message.id)"></button>
                        </div>
                    </div>
                </div>

                <div class="viewers-listing" v-if="viewers.length > 1" style="margin-bottom: 1em">
                    Viewers:&nbsp;&nbsp;&nbsp;
                    <span class="tag is-medium" v-for="viewer in viewers" style="margin-right: 3px">
                        <span>@{{ viewer.name }}</span>
                        <span v-if="isUserTyping(viewer.name)" style="color: #a6a6a6; margin-left: 6px;">(Typing)</span>
                    </span>
                </div>
            
                <p class="control">
                    <textarea class="textarea" placeholder="New message" v-model="new_message" :disabled="isPosting" name="message" style="min-height: 150px" @keydown="setUserTyping(this.userName)"></textarea>
                </p>
            
                <message-attachments upload-to="{{ route('tickets.file_upload') }}" v-on:upload-completed="addAttachment"></message-attachments>
                
                <p class="control" style="font-size: 1.1em" v-if="parseInt(ticket.status) !== 1">
                    <label class="checkbox">
                        <input type="checkbox" name="status" value="1" v-model="new_status" v-bind:true-value="1" v-bind:false-value="null">
                        Mark the ticket as complete
                    </label>
                </p>

                <p class="control" style="font-size: 1.1em" v-if="parseInt(ticket.status) == 1">
                    <label class="checkbox">
                        <input type="checkbox" name="status" value="0" v-model="new_status" v-bind:true-value="0" v-bind:false-value="null">
                        Reopen ticket as it is incomplete
                    </label>
                </p>

                <p class="control">
                    <button class="button is-wide is-primary" type="submit" @click.prevent="storeMessage()" :disabled="isPosting">Add new message</button>
                </p>
            </div>
        </div>
    </ticket-details>
</div>
@endsection

@section('inline-script')
<script>
    var ticket = {!! json_encode($ticket) !!};
</script>
@endsection