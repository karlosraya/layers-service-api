<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    protected $fillable = ['id', 'pww', 'pw', 'pullets', 'small', 'medium', 'large', 'extraLarge', 'jumbo', 'crack', 'spoiled', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
