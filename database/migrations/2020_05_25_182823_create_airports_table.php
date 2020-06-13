<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("code");

            $table->unsignedBigInteger("country_id");
            $table->foreign("country_id")->references("id")->on('countries')->onDelete('cascade');

            $table->unsignedBigInteger("city_id");
            $table->foreign("city_id")->references("id")->on('cities')->onDelete('cascade');;
            $table->string("timezone")->nullable();

            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();

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
        Schema::dropIfExists('airports');
    }
}
