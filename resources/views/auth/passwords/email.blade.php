@extends('layouts.client-area')

<!-- Main Content -->
@section('content')
<div class="container small-container">
    <h2><i class="fa fa-undo"></i><br>Reset Password</h2>
    @if (session('status'))
        <div class="message is-success">
            <div class="message-body">
                <span class="icon is-small" style="margin-top: 3px">
                    <i class="fa fa-check"></i>
                </span>

                <span>
                    {{ session('status') }}
                </span>
            </div>
        </div>
    @endif

    <form role="form" method="POST" action="{{ url('/password/email') }}">
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

        <p class="control">
            <button type="submit" class="button is-wide">
                Send Password Reset Link
            </button>
        </p>
    </form>
</div>
@endsection
