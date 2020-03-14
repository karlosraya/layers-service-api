<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_locks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('lockDate');
            $table->integer('pww')->nullable();
            $table->integer('pw')->nullable();
            $table->integer('pullets')->nullable();
            $table->integer('small')->nullable();
            $table->integer('medium')->nullable();
            $table->integer('large')->nullable();
            $table->integer('extraLarge')->nullable();
            $table->integer('jumbo')->nullable();
            $table->integer('crack')->nullable();
            $table->integer('spoiled')->nullable();
            $table->integer('feeds')->nullable();
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
        Schema::dropIfExists('data_locks');
    }
}
