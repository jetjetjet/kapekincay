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
            $table->bigIncrements('id');
            $table->string('orderinvoice');
            $table->integer('orderinvoiceindex');
            $table->unsignedBigInteger('orderboardid')->nullable();
            $table->string('ordertype');
            $table->string('ordercustname');
            $table->string('orderdate');
            $table->decimal('orderprice',16,0);
            $table->string('orderstatus');
            $table->string('orderdetail')->nullable();
            $table->string('orderpaymentmethod')->nullable();
            $table->boolean('orderpaid')->nullable();
            $table->decimal('orderpaidprice',16,0)->nullable();
            $table->string('orderpaidremark')->nullable();

            $table->boolean('orderactive');
            $table->boolean('ordervoid')->nullable();
            $table->integer('ordervoidedby')->nullable();
            $table->dateTime('ordervoidedat')->nullable();
            $table->string('ordervoidreason')->nullable();
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
