<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForgotPasswordTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forgot_password_tokens', function (Blueprint $table) {
            $table->string('user_id');
            $table->string('code')->unique();
            $table->date('expire_at');
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('user_id')->references('_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forgot_password_tokens');
    }
}
