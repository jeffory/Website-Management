@extends('layouts.client-area')

@section('content')
@include('partials/modal/delete-user-modal')

<div class="container main-container">
    <a href="{{ route('admin.index') }}" class="button is-primary">
        Back to admin
    </a>
    <hr>

    <data-table :data="accounts" :query="table_query" v-cloak></data-table>
</div>
@endsection

@section('inline-script')
<script>
    var accounts = {};
    accounts.columns = [
        {
            caption: 'Name',
            key: 'name',
            mobile_caption: true
        },
        {
            caption: 'Email',
            key: 'email',
            mobile_caption: true,
            link: '/client-area/management/email/'
        },
        {
            caption: 'Verified',
            key: 'is_verified',
            mobile_caption: true,
            type: 'tick'
        },
        {
            caption: 'Server access?',
            key: 'has_server_access',
            mobile_caption: true,
            type: 'tick'
        },
        {
            key: 'buttons',
            type: 'buttons',
            classes: 'is-skinny',
            buttons: [
                {
                    caption: 'Delete',
                    classes: 'is-danger mobile-half-width',
                    faIcon: 'times',
                    emit: 'delete-user'
                }
            ]
        }
    ]
    accounts.data = {!! json_encode($users) !!}
</script>
@endsection