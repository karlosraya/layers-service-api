<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataLock extends Model
{
    protected $fillable = ['id', 'lockDate', 'pww', 'pw', 'pullets', 'small', 'medium', 'large', 'extraLarge', 'jumbo', 'crack', 'spoiled', 'feeds', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];
    
    public $timestamps = false;
}
