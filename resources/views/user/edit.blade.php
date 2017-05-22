@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2>Account details</h2>

    <form role="form" action="{{ route('user.update') }}" method="post" style="max-width: 500px">
        {{ csrf_field() }}

        <div class="field">
            <label class="label">Name</label>

            <p class="control">
                <input type="text" name="name" class="input{{ $errors->has('name') ? ' is-danger' : '' }}" value="{{ $user['name'] }}" required>

                @if ($errors->has('name'))
                    <span class="help is-danger">{{ $errors->first('name') }}</span>
                @endif
            </p>
        </div>

        <hr>
        <h3>Password</h3>

        <div class="field">
            <label class="label">Current password</label>

            <p class="control">
                <input type="password" name="current_password" class="input{{ $errors->has('current_password') ? ' is-danger' : '' }}" value="">

                @if ($errors->has('current_password'))
                    <span class="help is-danger">{{ $errors->first('current_password') }}</span>
                @endif
            </p>
        </div>

        <div class="field">
            <label class="label">New password</label>

            <p class="control">
                <input type="password" name="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}" value="">

                @if ($errors->has('password'))
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                @endif
            </p>
        </div>

        <div class="field">
            <label class="label">New password confirmation</label>

            <p class="control">
                <input type="password" name="password_confirmation" class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}" value="">

                @if ($errors->has('password_confirmation'))
                    <span class="help is-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </p>
        </div>

        <p class="control">
            <button type="submit" class="button">
                Update
            </button>
        </p>
    </form>
</div>
@endsection