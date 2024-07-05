<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefrencetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refrencetables', function (Blueprint $table) {
            $table->id();
            $table->integer('tmpidfromtemptable')->nullable();
            $table->integer('depid')->nullable();
            $table->integer('maintempid')->nullable();
            $table->integer('notetempid')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refrencetables');
    }
}
