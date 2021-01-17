<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->integer('boardnumber');
            $table->string('boardfloor')->nullable();
            $table->integer('boardspace')->nullable();
            //$table->string('status')->nullable();
            $table->boolean('boardactive');
            $table->dateTime('boardcreatedat');
            $table->integer('boardcreatedby');
            $table->dateTime('boardmodifiedat')->nullable();
            $table->integer('boardmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
}
