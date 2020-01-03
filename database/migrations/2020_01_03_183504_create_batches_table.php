<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('houseId');
            $table->string('batch');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->integer('initialBirdBalance');
            $table->integer('startAge');
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
        Schema::dropIfExists('batches');
    }
}
