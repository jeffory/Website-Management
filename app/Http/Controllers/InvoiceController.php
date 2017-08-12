<?php

namespace App\Http\Controllers;

use App\Helpers\CarbonExtended;
use App\Http\Requests\StoreInvoice;
use App\Invoice;
use App\InvoiceClient;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use App\Facades\Flash;

class InvoiceController extends Controller
{
    /**
     * Create a new InvoiceController instance.
     */
    function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Invoice::class);

        $invoices = Invoice::with(['client' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with('payments')
            ->orderBy('id', 'desc')
            ->paginate(15);

        $invoices->map(function($invoice) {
            $invoice['total'] = '$ '. $invoice['total'];
            $invoice['owing'] = '$ '. $invoice['owing'];

            $invoice['date_issued'] = CarbonExtended::parse($invoice['date_issued'])->dateDiffForHumans();

            $invoice['client_name'] = $invoice['client']['name'];
            $invoice['_link'] = route('invoice.show', $invoice->id);
            unset($invoice['client']);

            return $invoice;
        });

        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Invoice::class);

        $clients = InvoiceClient::all();
        return view('invoice.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoice $request)
    {
        $invoice = new Invoice;

        $invoice->client_id = $request->client_id;
        $invoice->date_issued = CarbonExtended::now();
        $invoice->days_until_due = 30;
        $invoice->note = $request->note;
        $invoice->save();

        foreach ($request->items as $item) {
            $invoice->addItem($item['description'], $item['quantity'], $item['cost']);
        }


        Flash::set('Invoice created', 'success');

        return redirect()->route('invoice.show', ['invoice' => $invoice->id]);
    }

    /**
     * Display the specified invoice.
     *
     * @param  Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load('client', 'items');

        return view('invoice.show', compact('invoice'));
    }

    /**
     * Display a PDF of the specified invoice.
     *
     * @param  Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        \Debugbar::disable();

        $invoice->load('client', 'items');

        $pdf = new Dompdf();
        $view = View::make('invoice.show', ['invoice' => $invoice, 'pdf_mode' => true]);

        $pdf->loadHtml($view->render());
        $pdf->setPaper('A4');
        $pdf->isRemoteEnabled = true;
        $pdf->isHtml5ParserEnabled = true;
        $pdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]));

        $pdf->render();

        return response($pdf->output())->withHeaders([
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        $invoice->softDelete();
    }
}
