<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string("number");
            $table->unsignedBigInteger("flight_route_id");
            $table->foreign("flight_route_id")->references("id")->on('flight_routes')->onDelete('cascade');;

            $table->string("departure_time");
            $table->string("arrival_time");
            $table->boolean("sunday")->nullable()->default(false);
            $table->boolean("monday")->nullable()->default(false);
            $table->boolean("tuesday")->nullable()->default(false);
            $table->boolean("wednesday")->nullable()->default(false);
            $table->boolean("thursday")->nullable()->default(false);
            $table->boolean("friday")->nullable()->default(false);
            $table->boolean("saturday")->nullable()->default(false);

            $table->boolean("on_board_wifi")->nullable()->default(false);
            $table->boolean("meals")->nullable()->default(false);
            $table->boolean("seat_display")->nullable()->default(false);
            $table->boolean("power_plug")->nullable()->default(false);

            $table->json("online_seating")->nullable();

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
        Schema::dropIfExists('flights');
    }
}
