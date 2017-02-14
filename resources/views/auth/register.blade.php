@extends('layouts.client-area')

@section('content')
<div class="container small-container">
    <h2><i class="fa fa-pencil"></i><br>Register</h2>

    <form role="form" method="POST" action="{{ url('/register') }}">
        {{ csrf_field() }}

        <p class="control has-icon">
            <input class="input{{ $errors->has('name') ? ' is-danger' : '' }}" name="name" type="text" placeholder="Name" value="{{ old('name') }}" required autofocus>

            <span class="icon is-small">
                <i class="fa fa-user"></i>
            </span>

            @if ($errors->has('name'))
                <span class="help is-danger">{{ $errors->first('name') }}</span>
            @endif
        </p>

        <p class="control has-icon">
            <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" type="text" placeholder="Email address" value="{{ old('email') }}" required>

            <span class="icon is-small">
                <i class="fa fa-envelope"></i>
            </span>

            @if ($errors->has('email'))
                <span class="help is-danger">{{ $errors->first('email') }}</span>
            @endif
        </p>

        <p class="control has-icon">
            <input class="input{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" type="password" placeholder="Password" required>

            <span class="icon is-small">
                <i class="fa fa-lock"></i>
            </span>

            @if ($errors->has('password'))
                <span class="help is-danger">{{ $errors->first('password') }}</span>
            @endif
        </p>

        <p class="control has-icon">
            <input class="input" name="password_confirmation" type="password" placeholder="Password Confirmation" required>

            <span class="icon is-small">
                <i class="fa fa-lock"></i>
            </span>
        </p>

        <p class="control">
            <button type="submit" class="button is-wide">
                Register
            </button>
        </p>

    </form>
</div>
@endsection
