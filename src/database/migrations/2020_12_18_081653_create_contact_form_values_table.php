<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactFormValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_form_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_form_id');
            $table->json('payload');
            $table->string('status');
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
        Schema::dropIfExists('contact_form_values');
    }
}
