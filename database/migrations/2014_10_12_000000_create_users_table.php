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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('role');
            $table->string('ID')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('sex');
            $table->string('avatar');
            $table->string('isVerified');
            $table->softDeletes('deleted_at');
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
