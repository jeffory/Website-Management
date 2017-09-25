<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /**
     * Bind into Eloquent methods.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->invoice->fresh()->refreshTotal();
        });

        static::deleted(function ($item) {
            $item->invoice->fresh()->refreshTotal();
        });
    }

    /**
     * Invoice items belong to an invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Calculate the total.
     *
     * @return string
     */
    public function total()
    {
        return $this->cost * $this->quantity;
    }
}
