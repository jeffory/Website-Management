@extends('layouts.client-area')

@section('content')
<div class="container main-container">

    <ticket-details user-id="{{$user->id}}" username="{{$user->name}}" :data="ticket" inline-template v-cloak>
        <div>
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
                                Options
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
            
                        <p v-if="message.file.length > 0">
                            <ul style="display: inline-block;">
                                <li v-for="file in message.file" style="background-color: hsl(271, 100%, 71%); margin: 3px;" class="tag is-medium">
                                    <a :href="file.url" style="color: #fff">@{{ file.name }}</a>
                                </li>
                            </ul>
                        </p>
                    </div>
            
                    <div class="controls column has-text-right">
                        <button class="delete" @click="deleteMessage(message.id)"></button>
                    </div>
                </div>

                <div class="viewers-listing" v-if="viewers.length > 0">
                    Viewers:&nbsp;&nbsp;&nbsp;
                    <span class="tag is-medium" v-for="viewer in viewers" style="margin-right: 3px">
                        <span>@{{ viewer.name }}</span>
                        <span v-if="isUserTyping(viewer.name)" style="color: #a6a6a6; margin-left: 6px;">(Typing)</span>
                    </span>
                </div>
                <br>
            
                <p class="control">
                    <textarea class="textarea" placeholder="New message" v-model="new_message" :disabled="isPosting" name="message" style="min-height: 150px" @keydown="setUserTyping(this.userName)"></textarea>
                </p>
            
                <message-attachments upload-to="{{ route('tickets.file_upload') }}" v-on:upload-completed="addAttachment"></message-attachments>
            
                <p class="control">
                    <button class="button is-wide is-primary" @click="storeMessage()" :disabled="isPosting">Add new message</button>
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