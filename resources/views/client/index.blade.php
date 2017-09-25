@extends('layouts.client-area')

@section('content')
    <div class="container main-container">
        <h2>Invoice clients</h2>

        <data-table :data="table_data" :query="table_query" v-cloak></data-table>

        {{ $clients->links('partials.bulma-pagination') }}
    </div>
@endsection

@section('pre-inline-script')
    <script>
        window.table_data = {};

        table_data.columns = [
            {
                caption: 'Name',
                classes: 'is-inline-block-mobile',
                key: 'name',
                mobile_caption: true,
                _link: '/client-area/client/{id}'
            },
            {
                caption: 'Invoice count',
                classes: 'is-inline-block-mobile',
                key: 'invoice_count',
                mobile_caption: true
            }
//            {
//                key: 'buttons',
//                type: 'buttons',
//                classes: 'is-skinny',
//                buttons: [
//                    {
//                        caption: 'Edit',
//                        classes: 'is-primary mobile-half-width is-skinny',
//                        faIcon: 'times',
//                        emit: 'delete-email',
//                        link: '/client-area/clients/{id}'
//                    }
//                ]
//            }
        ];

        table_data.data = {!! json_encode($clients->toArray()['data']) !!};
    </script>
@endsection