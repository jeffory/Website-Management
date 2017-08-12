@extends('layouts.client-area')

@section('content')
    <div class="container main-container" style="padding: 3em 4em 6em">
        <h2 class="title is-1 is-marginless is-bold">New invoice</h2>

        <form action="{{ route('invoice.store') }}" method="post">
            {{ csrf_field() }}

            <div style="margin: 1.5em 0">
                <div class="control is-horizontal">
                    <div class="control-label" style="max-width: 200px">
                        <label for="date" class="label has-text-left">Invoice date:</label>
                    </div>

                    <div class="control-body">
                        <div class="control">
                            <input class="input date-picker" name="date_issued" type="text" value="{{ date('d/m/Y') }}"
                               style="width: auto;">

                            @if ($errors->has('date_issued'))
                                <span class="help is-danger">{{ $errors->first('date_issued') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label" style="max-width: 200px">
                        <label for="client" class="label has-text-left">Client:</label>
                    </div>

                    <div class="control-body">
                        <div class="control">
                            <div class="select">
                                <select name="client_id">
                                    <option></option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if ($errors->has('client_id'))
                            <span class="help is-danger">{{ $errors->first('client_id') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <invoice></invoice>

            <div class="level">
                <div class="level-left"></div>

                <div class="level-right">
                    <div class="level-item" style="margin-top: auto">
                        <a class="button is-link is-large" href="{{ route('invoice.index') }}">Cancel</a>
                        &nbsp;
                        <button class="button is-primary is-large">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('inline-script')
    <script>
        let dateFields = document.getElementsByClassName('date-picker');

        for (var i = 0; i < dateFields.length; i++) {
            new window.Pikaday({
                field: dateFields[i],
                format: 'D/MM/YYYY'
            });
        }
    </script>
@endsection
