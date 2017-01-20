@extends('layouts.app')

@section('content')
<div class="container main-container">
    <h2>Dashboard</h2>

    <p>Welcome back {{ Auth::user()->name }}!</p>
</div>
@endsection
