@extends('layouts.client-area')

@section('content')
    <div class="container main-container">
        <h2 class="title is-2">Invoices</h2>

        <div class="level">
            <div class="level-left is-centered-mobile">
                <a class="button is-primary" href="{{ route('invoice.create') }}">
                    <span class="icon is-small">
                        <i class="fa fa-plus"></i>
                    </span>

                    <span>New invoice</span>
                </a>
            </div>

            <div class="level-right">
                <div class="level-item">
                    <p class="control has-icon is-marginless is-full-width-mobile">
                        <input class="input" type="text" placeholder="Search invoices" v-model="table_query">

                        <span class="icon is-small">
                        <i class="fa fa-search"></i>
                    </span>
                    </p>
                </div>
            </div>
        </div>

        <data-table :data="table_data" :query="table_query" v-cloak></data-table>

        <!-- Pagination -->
        {{ $invoices->links('partials.bulma-pagination') }}
    </div>
@endsection

@section('pre-inline-script')
<script>
    window.table_data = {};

    table_data.columns = [
        {
            caption: 'Invoice#',
            classes: 'is-inline-block-mobile',
            key: 'id',
            mobile_caption: true,
            _link: '/client-area/invoice/{id}'
        },
        {
            caption: 'Issued',
            classes: 'is-inline-block-mobile',
            key: 'date_issued',
            mobile_caption: true
        },
        {
            caption: 'Client',
            classes: 'is-inline-block-mobile',
            key: 'client_name',
            mobile_caption: true
        },
        {
            caption: 'Total',
            classes: 'is-inline-block-mobile',
            key: 'total',
            mobile_caption: true
        },
        {
            caption: 'Owing',
            classes: 'is-inline-block-mobile',
            key: 'owing',
            mobile_caption: true
        }
    ];

    table_data.data = {!! json_encode($invoices->toArray()['data']) !!};
</script>
@endsection