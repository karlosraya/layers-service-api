<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $fillable = ['id', 'name', 'stockman', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
