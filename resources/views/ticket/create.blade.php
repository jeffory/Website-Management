@extends('layouts.app')

@section('content')
<div class="container main-container content">
    <h2>Create a new ticket</h2>

    <form role="form" method="POST" action="{{ url('/tickets') }}">
        {{ csrf_field() }}

        <p class="control">
            <input class="input{{ $errors->has('title') ? ' is-danger' : '' }}" name="title" type="text" placeholder="Title" value="{{ old('title') }}" required>

            @if ($errors->has('title'))
                <span class="help is-danger">{{ $errors->first('title') }}</span>
            @endif
        </p>

        <p class="control">
            <textarea class="textarea{{ $errors->has('message') ? ' is-danger' : '' }}" name="message" type="text" placeholder="Message" required>{{ old('message') }}</textarea>

            @if ($errors->has('message'))
                <span class="help is-danger">{{ $errors->first('message') }}</span>
            @endif
        </p>

        <p class="control">
            <button type="submit" class="button is-wide is-primary">
                Create
            </button>
        </p>
    </form>
</div>
@endsection