@extends('layouts.app')

@section('content')
<div class="container main-container">
    <h2><strong>Ticket:</strong> {{ $ticket->title }}</h2>
    
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

            <tabs :active="1">
                <tab label="Message" icon="pencil">
                    <p class="control">
                        <textarea class="textarea" placeholder="New message" v-model="newMessage" :disabled="isPosting" style="min-height: 150px"></textarea>
                    </p>
                </tab>

                <tab :label="'Attachments (' + attachmentCount + ')'" icon="paperclip">
                    <p class="control">
                        <dropzone id="file" url="https://httpbin.org/post" @vdropzone-success="fileUploaded" @vdropzone-removedFile="fileRemoved" :use-font-awesome="true" :max-file-size-in-mb="10" style="height: 100%" ref="dropzone"></dropzone>
                    </p>
                </tab>

                <tab label="Users/Permissions" icon="user">
                    <p>
                        User permissions would go here.
                    </p>
                </tab>
            </tabs>

            <div>attachments: @{{ attachmentCount }}</div>

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