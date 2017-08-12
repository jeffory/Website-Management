<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceClient extends Model
{
    /**
     * A client has many invoices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
