@extends('layouts.client-area')

@section('content')
<div class="main-container">
    <h2 class="title is-1 is-bold">Welcome back {{ $user->name }}!</h2>
    <br>

    <h3>Support tickets ({{ $ticket_count }})</h3>
    <div style="border: 1px solid #ddd; margin-bottom: 1em; padding: 2em 2em 1em;" class="content">
        <ul style="margin-bottom: 1em; margin-top: 0">
        @foreach($user->tickets as $ticket)
            <li>
                <a href="{{ route('tickets.show', $ticket) }}">"{{ $ticket->title }}" created {{ $ticket->created_at->diffForHumans() }}</a>
            </li>
        @endforeach
        </ul>
    </div>

    <br>

    <h3>Website problems</h3>
    <div style="border: 1px solid #ddd; padding: 2em 2em 1em;">
        <p class="has-text-centered">No problems found.</p>
    </div>

    {{--<div class="columns">--}}
        {{--<div class="column icon-box">--}}
            {{--<a href="{{ route('user.edit') }}">--}}
                {{--<div>--}}
                    {{--<i class="icon fa fa-user is-big"></i>--}}

                    {{--<h3>My Account Details</h3>--}}
                {{--</div>--}}
            {{--</a>--}}
        {{--</div>--}}

        {{--<div class="column icon-box">--}}
            {{--<a href="{{ route('tickets.index') }}">--}}
                {{--<div>--}}
                    {{--<i class="icon fa fa-ticket is-big"></i>--}}
                    {{--@if($user->isAdmin())--}}
                        {{--<h3>Support Tickets</h3>--}}
                    {{--@else--}}
                        {{--<h3>My Tickets</h3>--}}
                    {{--@endif--}}

                    {{--<p>--}}
                        {{--@if($user->isAdmin())--}}
                            {{--There are {{ $ticket_count }} ticket(s) open.--}}
                        {{--@else--}}
                            {{--You have {{ $ticket_count }} ticket(s) open.--}}
                        {{--@endif--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--</a>--}}
        {{--</div>--}}


        {{--<div class="column icon-box">--}}
            {{--<a href="{{ route('invoice.index') }}">--}}
                {{--<div>--}}
                    {{--<i class="icon fa fa-file-text-o is-big"></i>--}}
                    {{--<h3>Invoices</h3>--}}
                {{--</div>--}}
            {{--</a>--}}
        {{--</div>--}}

        {{--@if($user->hasServerAccess())--}}
            {{--<div class="column icon-box">--}}
                {{--<a href="{{ route('server.index') }}">--}}
                    {{--<div>--}}
                        {{--<i class="icon fa fa-server is-big"></i>--}}
                        {{--<h3>Website management</h3>--}}
                    {{--</div>--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--@endif--}}
    {{--</div>--}}

    {{--<div class="columns" style="margin-top: 1em">--}}
        {{--@if($user->isAdmin())--}}
            {{--<div class="column icon-box">--}}
                {{--<a href="{{ route('admin.index') }}">--}}
                    {{--<div>--}}
                        {{--<i class="icon fa fa-bolt is-big"></i>--}}
                        {{--<h3>Admin</h3>--}}
                    {{--</div>--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--@endif--}}
    {{--</div>--}}
</div>
@endsection
