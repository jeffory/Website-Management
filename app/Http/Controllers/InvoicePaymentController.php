<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('update', Invoice::class);

        $payments = InvoicePayment::latest()
                        ->with('invoice', 'invoice.client')
                        ->paginate(20);

        return response()->view('invoice_payments.index', compact('payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($invoice_id, Request $request)
    {
        $this->authorize('update', Invoice::class);

        $this->validate($request, [
            'date_paid' => 'required|date_format:d/m/Y',
            'amount_paid' => 'required|numeric'
        ]);

        InvoicePayment::create([
            'invoice_id' => $invoice_id,
            'date_paid' => Carbon::createFromFormat('d/m/Y', $request->date_paid),
            'amount_paid' => $request->amount_paid,
            'note' => $request->note
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoicePayment $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoicePayment $invoicePayment)
    {
        $this->authorize('update', Invoice::class);

        $invoicePayment->delete();
    }
}
