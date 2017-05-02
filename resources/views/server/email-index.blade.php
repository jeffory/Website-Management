@extends('layouts.client-area')

@section('content')
@include('partials/modal/new-email-modal')
@include('partials/modal/delete-email-modal')
@include('partials/modal/password-options-modal')

<div class="container main-container">
    <h2 class="title is-2">Email accounts for: {{ $domain }}</h2>
    
    <div class="level">
        <div class="level-left is-centered-mobile">
            <button class="button is-primary" @click="eventbus.$emit('show-modal', {id: 'new-email-modal'})">
                <span class="icon is-small">
                    <i class="fa fa-plus"></i>
                </span>

                <span>New account</span>
            </button>
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
</div>
@endsection

@section('inline-script')
<script>
    var accounts = {};
    // [['Email address', 'email'], ['Disk usage', 'humandiskused'], ['Disk limit', 'humandiskquota']]
    accounts.columns = [
        {
            caption: 'Email',
            key: 'email',
            mobile_caption: true
        },
        {
            caption: 'Disk usage',
            key: 'humandiskused',
            mobile_caption: true
        },
        {
            caption: 'Disk limit',
            key: 'humandiskquota',
            mobile_caption: true
        },
        {
            key: 'buttons',
            type: 'buttons',
            buttons: [
                {
                    caption: 'Delete',
                    classes: 'is-danger mobile-half-width',
                    faIcon: 'times',
                    emit: 'delete-email'
                },
                {
                    caption: 'Options',
                    classes: 'mobile-half-width',
                    faIcon: 'key',
                    emit: 'email-options'
                },
            ]
        }
    ]
    accounts.data = {!! json_encode($accounts) !!}
</script>
@endsection