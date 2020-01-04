<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['id', 'invoiceId', 'item', 'description',  'quantity', 'price'];

    public $timestamps = false;
}
