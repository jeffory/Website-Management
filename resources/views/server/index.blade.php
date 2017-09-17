@extends('layouts.client-area')

@section('content')
<div class="container main-container">
    <h2 class="title is-1 is-bold">Website management</h2>

    <div class="level">
        <div class="level-left">
            <p>
                From this area you can manage some aspects of your websites, such as emails.
            </p>
        </div>

        <div class="level-right">
            <div class="level-item">
                <p class="control has-icon is-marginless is-full-width-mobile">
                    <input class="input" type="text" placeholder="Search accounts" v-model="table_query">
                
                    <span class="icon is-small">
                        <i class="fa fa-search"></i>
                    </span>
                </p>
            </div>
        </div>
    </div>
    
    <data-table :data="accounts" :query="table_query" v-cloak></data-table>

    <div>
        <p>Note: Disk usage information is not realtime and may be up to a day old.</p>
    </div>

</div>
@endsection

@section('pre-inline-script')
<script>
    var accounts = {}
    accounts.columns = [
        {
            caption: 'Domain',
            key: 'domain',
            mobile_caption: true
        },
        {
            caption: 'Email Limit',
            key: 'max-emails',
            mobile_caption: true
        },
        {
            caption: 'Disk used',
            key: 'disk-used-percentage',
            mobile_caption: true
        },
        {
            key: 'buttons',
            type: 'buttons',
            classes: 'is-skinny',
            buttons: [
                {
                    caption: 'Email accounts',
                    classes: 'is-primary mobile-half-width',
                    faIcon: 'envelope',
                    link: '/client-area/management/email/{domain}'
                },
                {
                    caption: 'CPanel',
                    classes: 'is-orange mobile-half-width <?php if (!Auth::user()->isAdmin()) echo 'is-hidden' ?>',
                    faIcon: 'gear',
                    link: '/client-area/management/cpanel/{domain}'
                }
            ]
        }
    ]
    accounts.data = {!! json_encode($accounts) !!}
</script>
@endsection