<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceClient;
use Illuminate\Http\Request;

class InvoiceClientController extends Controller
{
    /**
     * Create a new InvoiceController instance.
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('create', Invoice::class);

        $clients = InvoiceClient::paginate(15);
        $clients->map(function ($client) {
            $client->invoice_count = $client->invoice_count();
        });

        return view('client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Invoice::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Invoice::class);

        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postcode' => 'required|alpha_dash'
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InvoiceClient  $invoiceClients
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceClient $invoiceClients)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InvoiceClient  $invoiceClients
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceClient $invoiceClients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoiceClient  $invoiceClients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceClient $invoiceClients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoiceClient  $invoiceClients
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceClient $invoiceClients)
    {
        //
    }
}
