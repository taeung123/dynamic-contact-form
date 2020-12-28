<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactFormInputValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_form_input_validations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_form_input_id');
            $table->string('validation_name');
            $table->string('validation_value');
            $table->foreign('contact_form_input_id')->references('id')->on('contact_form_inputs')->onDelete('cascade');
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
        Schema::dropIfExists('contact_form_input_validations');
    }
}
