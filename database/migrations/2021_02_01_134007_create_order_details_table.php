<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdetail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('odorderid');
            $table->integer('odmenuid');
            $table->integer('odqty');
            $table->decimal('odprice',16,0);
            $table->decimal('odtotalprice',16,0);
            $table->string('odremark')->nullable();
            $table->integer('odindex');

            $table->boolean('odactive');
            $table->dateTime('odcreatedat');
            $table->integer('odcreatedby');
            $table->dateTime('odmodifiedat')->nullable();
            $table->integer('odmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
