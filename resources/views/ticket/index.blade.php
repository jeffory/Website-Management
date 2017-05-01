@extends('layouts.client-area')

@section('content')
<div class="container main-container" id="tickets-management">
    <h2>My tickets</h2>

    <div class="level">
        <div class="level-left is-centered-mobile">
            <a class="button is-primary {{ (!$user->isVerified()) ? 'is-disabled': '' }}" href="{{ route('tickets.create') }}">
                <span class="icon is-small">
                    <i class="fa fa-plus"></i>
                </span>
                <span>Create new ticket</span>
            </a>
        </div>

        <div class="level-right">
            <div class="field is-horizontial">

                <form method="get">
                    <strong style="margin-right: 1em">Show tickets that are:</strong>
                
                    <label class="radio">
                        <input type="radio" name="status" value="0" {{ ($ticket_status_sort === 0) ? ' checked': '' }} onChange="this.form.submit()">
                        Open
                    </label>

                    <label class="radio">
                        <input type="radio" name="status" {{ ($ticket_status_sort === 1) ? ' checked': '' }} value="1" onChange="this.form.submit()">
                        Closed
                    </label>
                </form>

            </div>
        </div>
    </div>

    @if (! $user->isVerified())
    <article class="message is-warning">
        <div class="message-body">
            Please verify your email to enable support ticket creation. If you have not recieved the email, you may <a href="{{ route('user.sendVerification') }}">resend it</a>.
        </div>
    </article>
    @endif

    @if ($tickets->count() == 0)
        <p style="font-size: 13pt; padding: 1em 0; text-align: center">

            @if ($ticket_status_sort === '0')
                No open support tickets found.<br>
            @else
                No closed support tickets found.<br>
            @endif
            <i class="fa fa-smile-o"></i>
        </p>
    @else
        <data-table :data="tickets" :query="table_query" v-cloak></data-table>

        <!-- Pagination -->
        {{ $tickets->appends(['status' => $ticket_status_sort])->links('partials.bulma-pagination') }}
    @endif
</div>
@endsection

@section('inline-script')
<script>
    var tickets = {}
    tickets.columns = [
        {
            caption: 'Status',
            classes: 'is-inline-block-mobile',
            key: 'status',
            type: 'tags',
            tags: {
                0: {
                    classes: 'is-info',
                    text: 'Open'
                },
                1: {
                    classes: 'is-danger',
                    text: 'Closed'
                }
            }
        },
        {
            caption: 'Title',
            classes: 'is-inline-block-mobile',
            key: 'title',
            mobile_caption: true
        },
        {
            caption: 'Creator',
            classes: 'mobile-full-width',
            key: 'user_name',
            mobile_caption: true
        },
        {
            caption: 'Last update',
            classes: 'mobile-full-width',
            key: 'updated_at',
            mobile_caption: true
        }
    ]
    tickets.data = {!! json_encode($tickets->toArray()['data']) !!}
</script>
@endsection