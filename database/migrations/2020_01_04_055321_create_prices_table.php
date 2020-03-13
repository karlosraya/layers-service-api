<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customerId');
            $table->float('pww')->nullable();
            $table->float('pw')->nullable();
            $table->float('pullets')->nullable();
            $table->float('small')->nullable();
            $table->float('medium')->nullable();
            $table->float('large')->nullable();
            $table->float('extraLarge')->nullable();
            $table->float('jumbo')->nullable();
            $table->float('crack')->nullable();
            $table->float('spoiled')->nullable();
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
        Schema::dropIfExists('prices');
    }
}
