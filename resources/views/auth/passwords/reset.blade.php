@extends('layouts.client-area')

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

    <form role="form" method="POST" action="{{ url('/password/reset') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <p class="control has-icon">
            <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" type="text" placeholder="Email address" value="{{ $email or old('email') }}" required autofocus>

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
                Reset Password
            </button>
        </p>
    </form>
</div>

{{-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
