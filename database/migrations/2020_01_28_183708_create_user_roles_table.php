<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userId');
            $table->enum('role', ['administrator', 
                                  'manageUsers', 
                                  'lockData', 
                                  'viewFeedsDelivery', 
                                  'editFeedsDelivery', 
                                  'deleteFeedsDelivery',
                                  'viewEggProduction', 
                                  'editEggProduction', 
                                  'deleteEggProduction', 
                                  'viewGradedEggs', 
                                  'editGradedEggs',
                                  'viewInvoice',
                                  'editInvoice',
                                  'deleteInvoice',
                                  'viewHouse',
                                  'editHouse',
                                  'viewBatch',
                                  'editBatch',
                                  'viewCustomer',
                                  'editCustomer',
                                  'viewPrice',
                                  'editPrice']);
            $table->string('houseName')->nullable();
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
        Schema::dropIfExists('user_roles');
    }
}
