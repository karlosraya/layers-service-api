<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedsDelivery extends Model
{
 	protected $fillable = ['id', 'deliveryDate', 'deliveryReceiptNo', 'delivery', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
