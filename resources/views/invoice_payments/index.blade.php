@extends('layouts.client-area')

@section('content')
    <div class="container main-container">
        <h2 class="title is-1 is-bold">Invoice payments</h2>

        <data-table :data="table_data" :query="table_query" v-cloak></data-table>

        <!-- Pagination -->
        {{ $payments->links('partials.bulma-pagination') }}
    </div>
@endsection

@section('pre-inline-script')
    <script>
        window.table_data = {};

        table_data.columns = [
            {
                caption: 'Invoice#',
                classes: 'is-inline-block-mobile',
                key: 'invoice_id',
                mobile_caption: true,
                _link: '/client-area/invoice/{id}'
            },
            {
                caption: 'Date paid',
                classes: 'is-inline-block-mobile',
                key: 'date_paid',
                mobile_caption: true
            },
            {
                caption: 'Amount',
                classes: 'is-inline-block-mobile',
                key: 'amount_paid',
                mobile_caption: true
            },
            {
                caption: 'Note',
                classes: 'is-inline-block-mobile',
                key: 'note',
                mobile_caption: true
            }
        ];

        table_data.data = {!! json_encode($payments->toArray()['data']) !!};
    </script>
@endsection