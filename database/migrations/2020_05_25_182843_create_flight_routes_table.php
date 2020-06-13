<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("arrival_airport_id");
            $table->foreign("arrival_airport_id")->references("id")->on('airports')->onDelete('cascade');;
            $table->unsignedBigInteger("departure_airport_id");
            $table->foreign("departure_airport_id")->references("id")->on('airports')->onDelete('cascade');;
            $table->string("duration");
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
        Schema::dropIfExists('flight_routes');
    }
}
