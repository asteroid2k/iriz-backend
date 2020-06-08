<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_data', function (Blueprint $table) {
            //$table->string('course_name');
            $table->string('course_code')->unique();
            $table->string('data_file');
            $table->string('pop');
            $table->string('cumm_total');
            $table->string('num_days');
            $table->json('data');
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('course_id')->references('course_id')->on('courses_infos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_infos');
    }


}
