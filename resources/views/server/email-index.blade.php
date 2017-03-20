@extends('layouts.client-area')

@section('content')
@include('partials/new-email-modal')
@include('partials/delete-email-modal')
@include('partials/password-options-modal')

<div class="container main-container">
    <h2 class="title is-2">Email accounts for: {{ $domain }}</h2>
    
    <div class="level">
        <div class="level-left">
            <button class="button is-primary" @click="showModal('new_email')">
                <span class="icon is-small">
                    <i class="fa fa-plus"></i>
                </span>

                <span>New account</span>
            </button>
        </div>

        <div class="level-right">
            <div class="level-item">
                <p class="control has-icon is-marginless">
                    <input class="input" type="text" placeholder="Search accounts" v-model="query">
                
                    <span class="icon is-small">
                        <i class="fa fa-search"></i>
                    </span>
                </p>
            </div>
        </div>
    </div>
    
    <data-table :data="accounts" :columns="[['Email address', 'email'], ['Disk usage', 'humandiskused'], ['Disk limit', 'humandiskquota']]" :query="query" inline-template v-cloak>
        <table class="table data-table">
            <thead>
                <tr>
                    <th v-for="(column, index) in view_columns">
                        <span>@{{ column }}</span>

                        <div style="float: right; cursor: pointer; margin-top: 4px">
                            <span @click="column_toggle_sort(index)" class="icon is-small">
                                <i v-if="sort.index == index &amp;&amp; sort.desc" class="fa fa-sort-desc"></i>
                                <i v-else-if="sort.index == index &amp;&amp; !sort.desc" class="fa fa-sort-asc"></i>
                                <i v-else style="opacity:.4;" class="fa fa-sort"></i>
                            </span>
                        </div>
                    </th>

                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="row in view_data">
                    <td v-for="(column, index) in row">
                        @{{ column }}
                    </td>

                    <td class="has-text-centered">
                        <button class="button is-danger" @click="showModal('delete_email', { email: row[0] })">
                                <span class="icon is-small">
                                    <i class="fa fa-times"></i>
                                </span>

                                <span>Delete</span>
                        </button>

                        <button class="button" @click="showModal('change_email_password', { email: row[0] })">
                            <span class="icon is-small">
                                <i class="fa fa-key"></i>
                            </span>

                            <span>Options</span>
                        </button>
                    </td>
                </tr>

                <tr v-if="view_data.length == 0">
                    <td :colspan="view_columns.length" class="has-text-centered">No data specified.</td>
                </tr>
            </tbody>
        </table>
    </data-table>

    <hr>
</div>
@endsection

@section('inline-script')
<script>
    var accounts = {!! json_encode($accounts) !!};
</script>
@endsection