<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	protected $fillable = ['id', 'userId', 'role', 'houseName', 'feeds', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
