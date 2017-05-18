@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2>Admin management</h2>

    <div class="column is-3 icon-box">
        <a href="{{ route('admin.user_index') }}">
            <div>
                <i class="icon fa fa-user is-big"></i>
                <h3>Users</h3>
            </div>
        </a>
    </div>
</div>
@endsection