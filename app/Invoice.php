<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * Bind into Eloquent methods.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->view_key = str_random(20);
        });

        static::deleting(function ($invoice) {
            $invoice->items->each->delete();
            $invoice->payments->each->delete();
        });
    }

    /**
     * Ensure a carbon date instance is always returned.
     * NOTE: This gets run quite late when the model is converted to an array.
     *
     * @param $date
     *
     * @return \Carbon\Carbon
     */
    public function getDateIssuedAttribute($date)
    {
        if (preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $date)) {
            return \App\Helpers\CarbonExtended::createFromFormat('d/m/Y', $date);
        }

        if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date)) {
            return \App\Helpers\CarbonExtended::parse($date);
        }

        return $date;
    }


    /**
     * Invoice items for the current invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Client the invoice belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(InvoiceClient::class);
    }

    /**
     * Payments made to the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * Add an item line to the current invoice.
     *
     * @param $description
     * @param $quantity
     * @param $cost
     */
    public function addItem($description, $quantity, $cost)
    {
        $invoice_item = new InvoiceItem();
        $invoice_item->description = $description;
        $invoice_item->quantity = $quantity;
        $invoice_item->cost = ltrim($cost, '$ ');
        $invoice_item->invoice_id = $this->id;
        $invoice_item->save();

        $this->refreshTotal();
    }

    public function refreshTotal()
    {
        $this->total = $this->calculateTotal();
        $this->owing = $this->calculateTotal() - $this->paymentTotal();
        $this->save();
    }

    /**
     * Calculate invoice total.
     *
     * @return float
     */
    public function calculateTotal()
    {
        $total = 0.00;

        foreach ($this->items as $item) {
            $total += $item->total();
        }

        return $total;
    }

    /**
     * Calculate the total of the payments to this invoice.
     *
     * @return float
     */
    public function paymentTotal()
    {
        $payments_total = 0;

        foreach ($this->payments as $payment) {
            $payments_total += $payment->amount_paid;
        }

        return $payments_total;
    }

    /**
     * Calculate the balance owing.
     *
     * @return float
     */
    public function balanceDue()
    {
        return $this->total - $this->paymentTotal();
    }
}
