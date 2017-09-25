<?php

namespace App\Console\Commands;

use App\InvoicePayment;
use Illuminate\Console\Command;

class InvoiceRecalculateTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the invoice totals for payments and generate missing viewing tokens.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (\App\Invoice::all() as $invoice) {
            $invoice->total = $invoice->refreshTotal();

            if ($invoice->view_key === null) {
                $invoice->view_key = str_random(32);
                $invoice->save();
            }
        }
    }
}
