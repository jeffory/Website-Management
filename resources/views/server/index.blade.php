@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2 class="title is-2">Website management</h2>

    <p>
        From this area you can manage some aspects of your websites, such as emails.
    </p>

    <div class="panel" style="max-width: 650px">
        <h3 class="panel-heading">
            Accounts
        </h3>

{{--         <div class="panel-block">
            <p class="control has-icon" style="margin-bottom: 0;">
                <input class="input is-small" type="text" placeholder="Search">

                <span class="icon is-small">
                    <i class="fa fa-search"></i>
                </span>
            </p>
        </div> --}}

        <div class="panel-block">
            <table class="table">
                <thead>
                    <tr>
                        <th>Domain</th>
                        <th>Disk usage</th>
                        <th>Manage</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td style="line-height: 2.8em">
                                {{ $account->domain }}
                            </td>
                    
                            <td>
                                {{ round( ($account['disk-used'] / $account['disk-limit']) * 100, 2) }}%
                            </td>
                    
                            <td class="has-text-right">
                                <a class="button is-primary" href="{{ route('server.show', ['domain' => $account->domain]) }}">
                                    <span>Email</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <p>Note: Disk usage information is not realtime and may be up to a day old.</p>
        </div>
    </div>

</div>
@endsection