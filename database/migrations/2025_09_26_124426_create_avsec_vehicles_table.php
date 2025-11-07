<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvsecVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('avsec_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_ara_id');
//            $table->foreign('staff_ara_id')->references('id')->on('staff_member_details')->onDelete('cascade');
            $table->string('car_model', 191);
            $table->string('colour', 191);
            $table->string('brand', 191);
            $table->string('reg_number', 191);
            $table->string('sticker_number', 191);
            $table->bigInteger('attended_by_user_id');
//            $table->foreign('attended_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('registration_cert', 191);
            $table->string('proof_of_ownership', 191);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avsec_vehicles');
    }
}
