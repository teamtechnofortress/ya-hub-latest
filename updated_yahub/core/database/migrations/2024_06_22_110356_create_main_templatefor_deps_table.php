<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainTemplateforDepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_templatefor_deps', function (Blueprint $table) {
            $table->id();
            $table->string('tempName')->nullable();
            $table->string('refnumber')->nullable();
            $table->string('paymentType')->nullable();
            $table->date('dueDate')->nullable();
            $table->text('Notes')->nullable();
            $table->integer('depid')->nullable();
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
        Schema::dropIfExists('main_templatefor_deps');
    }
}
