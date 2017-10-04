@component('mail::message')
Hello,

Thank you for your recent business with us. Please find attached a detailed copy of your invoice.

The amount due is {{ '$'. number_format($invoice->balanceDue(), 2) }}, to be paid within {{ $invoice->days_until_due }} days.

An online copy of the invoice is available here:

@component('mail::button', ['url' => route('invoice.show', ['invoice' => $invoice->id, 'view_key' => $invoice->view_key ])])
View Invoice
@endcomponent

If you have any questions or concerns with this invoice, please don't hesitate to get in touch with us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent