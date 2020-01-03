<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
	protected $fillable = ['id', 'batchId', 'houseId', 'reportDate', 'feeds', 'eggProduction', 'cull', 'mortality', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
