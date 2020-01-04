<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['id', 'firstName', 'lastName', 'address', 'email', 'phoneNumber', 'companyName', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
