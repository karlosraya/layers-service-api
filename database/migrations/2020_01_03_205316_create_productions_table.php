<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('batchId');
            $table->bigInteger('houseId');
            $table->date('reportDate');
            $table->double('feeds', 8, 2);
            $table->integer('eggProduction');
            $table->integer('cull')->nullable();
            $table->integer('mortality')->nullable();
            $table->string('lastInsertUpdateBy');
            $table->dateTime('lastInsertUpdateTS');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
}
