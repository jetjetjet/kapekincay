<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('orderboardid')->nullable();
            $table->string('orderinvoice');
            $table->string('ordercustname');
            $table->string('ordertype');
            $table-dateTime('orderdate');
            $table->string('orderdetail')->nullable();
            $table->string('orderstatus');

            $table->boolean('orderactive');
            $table->dateTime('ordercreatedat');
            $table->integer('ordercreatedby');
            $table->dateTime('ordermodifiedat')->nullable();
            $table->integer('ordermodifiedby')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
