<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradedEggs extends Model
{
	protected $fillable = ['id', 'inputDate', 'pww', 'pw', 'pullets', 'small', 'medium', 'large', 'extraLarge', 'jumbo', 'crack', 'spoiled', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
