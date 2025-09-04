<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQaLetterTable extends Migration
{
    public function up()
    {
        Schema::create('qa_letter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Organization', 191);
            $table->string('external_reference', 191);
            $table->string('internal_reference', 191);
            $table->string('department', 191);
            $table->text('description');
            $table->string('administrator_ara_id', 191);
            $table->string('file_path', 191);
            $table->bigInteger('category_id');
//            $table->foreign('category_id')->references('id')->on('qa_categories')->onDelete('cascade');
            $table->date('for_date');
            $table->string('status', 191);
            $table->dateTime('status_last_changed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qa_letter');
    }
}
