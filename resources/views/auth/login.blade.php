@extends('layouts.minimal-dialog')

@section('content')

<div class="small-container">
    <h2><i class="fa fa-lock"></i><br>Login</h2>

    <form role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <p class="control has-icon">
            <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" type="text" placeholder="Email address" value="{{ old('email') }}" required autofocus>

            <span class="icon is-small">
                <i class="fa fa-envelope"></i>
            </span>

            @if ($errors->has('email'))
                <span class="help is-danger">{{ $errors->first('email') }}</span>
            @endif
        </p>

        <p class="control has-icon">
            <input class="input{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" type="password" placeholder="Password"  required>

            <span class="icon is-small">
                <i class="fa fa-lock"></i>
            </span>

            @if ($errors->has('password'))
                <span class="help is-danger">{{ $errors->first('password') }}</span>
            @endif
        </p>

        <p class="control">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
            </label>
        </p>

        <p class="control">
            <button type="submit" class="button is-wide">
                Sign in
            </button>
        </p>

        <p class="has-text-right">
            <a class="btn btn-link" href="{{ url('/password/reset') }}">
                Forgot Your Password?
            </a>
        </p>
    </form>

    <a href="/">&larr; Back to home page</a>
</div>
@endsection
