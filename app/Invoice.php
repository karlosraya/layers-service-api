<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['id', 'customerId', 'invoiceNumber', 'invoiceDate', 'subTotal', 'discount', 'total', 'amountPaid', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
