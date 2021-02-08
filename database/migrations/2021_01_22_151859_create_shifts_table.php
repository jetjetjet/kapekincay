<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shiftuserid');
            $table->integer('shiftindex');
            $table->datetime('shiftstart');
            $table->datetime('shiftclose')->nullable();
            $table->decimal('shiftstartcash',16,0);
            $table->decimal('shiftstartcoin',16,0)->nullable();
            $table->decimal('shiftendcash',16,0)->nullable();
            $table->decimal('shiftendcoin',16,0)->nullable();
            $table->string('shiftenddetail')->nullable();
            $table->string('shiftdeleteremark')->nullable();
            $table->boolean('shiftactive');
            $table->dateTime('shiftcreatedat');
            $table->integer('shiftcreatedby');
            $table->dateTime('shiftmodifiedat')->nullable();
            $table->integer('shiftmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
