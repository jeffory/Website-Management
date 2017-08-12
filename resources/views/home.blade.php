@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2>Welcome back {{ $user->name }}!</h2>
    <br>

    <div class="columns">
        <div class="column icon-box">
            <a href="{{ route('user.edit') }}">
                <div>
                    <i class="icon fa fa-user is-big"></i>

                    <h3>My Account Details</h3>
                </div>
            </a>
        </div>

        <div class="column icon-box">
            <a href="{{ route('tickets.index') }}">
                <div>
                    <i class="icon fa fa-ticket is-big"></i>
                    @if($user->isAdmin())
                        <h3>Support Tickets</h3>
                    @else
                        <h3>My Tickets</h3>
                    @endif

                    <p>
                        @if($user->isAdmin())
                            There are {{ $ticket_count }} ticket(s) open.
                        @else
                            You have {{ $ticket_count }} ticket(s) open.
                        @endif
                    </p>
                </div>
            </a>
        </div>


        <div class="column icon-box">
            <a href="{{ route('invoice.index') }}">
                <div>
                    <i class="icon fa fa-file-text-o is-big"></i>
                    <h3>Invoices</h3>
                </div>
            </a>
        </div>

        @if($user->hasServerAccess())
            <div class="column icon-box">
                <a href="{{ route('server.index') }}">
                    <div>
                        <i class="icon fa fa-server is-big"></i>
                        <h3>Website management</h3>
                    </div>
                </a>
            </div>
        @endif
    </div>

    <div class="columns" style="margin-top: 1em">
        @if($user->isAdmin())
            <div class="column icon-box">
                <a href="{{ route('admin.index') }}">
                    <div>
                        <i class="icon fa fa-bolt is-big"></i>
                        <h3>Admin</h3>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
