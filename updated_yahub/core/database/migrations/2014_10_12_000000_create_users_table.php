<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('username',50);
            $table->integer('role')->default(2)->comment("1->Admin 2->Agency 3->Client");
            $table->integer('is_active')->default(1);
            $table->integer('is_online')->default(0);
            $table->string('email',50)->unique();
            $table->string('agency_contact',50)->nullable();
            $table->text('assigned_clients')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',255);
            
            $table->integer('upload_limit_in_mbs')->default(4);
            
            $table->integer('max_conversations_in_inbox')->default(10);
            
            $table->integer('max_projects_per_client')->default(10);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
