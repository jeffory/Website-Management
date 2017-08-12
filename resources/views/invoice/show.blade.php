@extends('layouts.client-area')

@section('content')
    <div class="container main-container">
        <header>
            <div class="level" style="margin-bottom: 2em;">
                <div class="level-left level-align-top">
                    <div>
                        <h2 class="title is-1 is-marginless is-bold">Invoice</h2>
                        <span>{{ Carbon\Carbon::parse($invoice->date_issued)->format('j F Y') }}</span>
                    </div>
                </div>

                <div class="level-right level-align-top">
                    <span class="title is-2 is-marginless is-bold invoice-number">#{{ $invoice->id }}</span>
                </div>
            </div>
        </header>

        <div class="level">
            <div class="level-left level-align-top">
                <div class="level-item">
                    <div>
                        <h3 class="title is-4" style="margin-bottom: .3em;">To</h3>

                        <ul class="invoice-details">
                            <li><strong>{{ $invoice->client->name }}</strong></li>
                            @if ($invoice->client->address)
                                <li>{{ $invoice->client->address }},</li>
                            @endif
                            <li>
                                {{ !empty($invoice->client->city) ? $invoice->client->city. ',' : '' }}
                                {{ !empty($invoice->client->state) ? $invoice->client->state. ',' : '' }}
                                {{ $invoice->client->postcode }}
                            </li>

                            @if($invoice->client->abn)
                                <li>ABN: {{ $invoice->client->abn }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="level-right level-align-top">
                <div class="level-item">
                    <div>
                        <h3 class="title is-4" style="margin-bottom: .3em; min-width: 250px;">From</h3>

                        <ul class="invoice-details">
                            <li><strong>Keith McGahey</strong></li>
                            <li>ABN: 32 446 571 043</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <table class="invoice-items">
            <thead>
            <tr>
                <th class="has-text-left">Description</th>
                <th>Cost</th>
                <th>Amount</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <span style="max-width: 80vw">{!! str_replace("\n", '<br>', strip_tags($item->description)) !!}</span>
                    </td>

                    <td class="numeric">
                        {{ '$'. $item->cost }}
                    </td>

                    <td class="numeric">
                        {{ $item->quantity }}
                    </td>

                    <td class="numeric has-text-right">
                        {{ '$'. number_format($item->total(), 2) }}
                    </td>
                </tr>
            @endforeach

            <tr class="totals-row">
                <td colspan="1"></td>
                <td colspan="2" class="has-text-right">Total</td>
                <td class="has-text-right">{{ '$'. number_format($invoice->total, 2) }}</td>
            </tr>

            <tr class="totals-row">
                <td colspan="1"></td>
                <td colspan="2" class="has-text-right">Payments</td>
                <td class="has-text-right">{{ '$'. number_format($invoice->paymentTotal(), 2) }}</td>
            </tr>

            <tr class="totals-row balance-row">
                <td style="color: #444">
                    Payment terms:
                    {{ $invoice->days_until_due. ' '. str_plural('day') }}<br>
                    ({{ Carbon\Carbon::parse($invoice->date_issued)->addDays($invoice->days_until_due)->format('j F Y') }})
                </td>
                <td colspan="2" class="has-text-right">Balance Due</td>
                <td class="currency has-text-right">{{ '$'. number_format($invoice->balanceDue(), 2) }}</td>
            </tr>
            </tbody>
        </table>

        <div class="level">
            <div class="level-left">
                <div class="invoice-note">
                    {!! str_replace("\n", '<br>', strip_tags($invoice->note)) !!}
                </div>
            </div>

            @if (count($invoice->payments) > 0)
                <div class="level-right"  style="width: 40%">
                    <div>
                        <h3>Payments applied</h3>

                        <table>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>
                                        {{ Carbon\Carbon::parse($payment->date_paid)->format('j/m/Y') }}
                                    </td>
                                    <td>
                                        {{ $payment->amount_paid }}
                                    </td>
                                    <td>
                                        {{ $payment->note }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </div>

        @can('update', $invoice)
        <hr>

        <div>
            <h3>Enter a payment</h3>
            
            <form action="{{ route('invoice.payment_store', ['invoice' => $invoice->id]) }}" method="post" style="max-width: 500px">
                {{ csrf_field() }}

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label for="date_paid" class="label has-text-left">Payment date:</label>
                    </div>

                    <div class="control-body">
                        <div class="control">
                            <input class="input date-picker" name="date_paid" type="text" value="{{ date('d/m/Y') }}">

                            @if ($errors->has('date_paid'))
                                <span class="help is-danger">{{ $errors->first('date_paid') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="control is-horizontal">
                    <div class="control-label">
                        <label for="amount_paid" class="label has-text-left">Amount:</label>
                    </div>

                    <div class="control-body">
                        <div class="control">
                            <input class="input" name="amount_paid" type="text" placeholder="0.00">

                            @if ($errors->has('amount_paid'))
                                <span class="help is-danger">{{ $errors->first('amount_paid') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="control">
                    <div class="control-label">
                        <label for="note" class="label has-text-left">Note:</label>
                    </div>

                    <div class="control-body">
                        <div class="control">
                            <input class="input" name="note" type="input">

                            @if ($errors->has('note'))
                                <span class="help is-danger">{{ $errors->first('note') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="control">
                    <div class="control">
                        <button type="submit" class="button is-primary">Save payment</button>
                    </div>
                </div>

            </form>
        </div>
        @endcan
    </div>
@endsection

@section('inline-script')
    <script>
        let dateFields = document.getElementsByClassName('date-picker');

        for (var i = 0; i < dateFields.length; i++) {
            new window.Pikaday({
                field: dateFields[i],
                format: 'DD/MM/YYYY'
            });
        }
    </script>
@endsection