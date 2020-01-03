<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['id', 'houseId', 'batch', 'startDate', 'endDate', 'initialBirdBalance', 'startAge', 'lastInsertUpdateBy', 'lastInsertUpdateTS'];

    public $timestamps = false;
}
