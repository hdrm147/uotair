<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("flight_class_id");
            $table->foreign("flight_class_id")->references("id")->on("flight_classes")->onDelete('cascade');

            $table->unsignedBigInteger("flight_id");
            $table->foreign("flight_id")->references("id")->on("flights")->onDelete('cascade');

            $table->integer("capacity");

            $table->double("minimum_adult_fare");
            $table->double("maximum_adult_fare");
            $table->double("children_fare_percentage");
            $table->double("number_of_bags");
            $table->double("bag_weight_limit");



            $table->double("fare_increment")->default(0);
            $table->integer("fare_every_days")->default(false);
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
        Schema::dropIfExists('flights_classes');
    }
}
