<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactFormInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_form_inputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_form_id');
            $table->string('type_input');
            $table->string('label');
            $table->string('slug');
            $table->integer('order')->nullable();
            $table->string('note')->nullable();
            $table->string('placeholder')->nullable();
            $table->foreign('contact_form_id')->references('id')->on('contact_forms')->onDelete('cascade');
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
        Schema::dropIfExists('contact_form_inputs');
    }
}
