<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayloadSlugOnContactFormValueTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_form_values', function (Blueprint $table) {
            $table->json('payload_slug')->after('payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_form_values', function (Blueprint $table) {
            $table->dropColumn('payload_slug');
        });
    }
}
