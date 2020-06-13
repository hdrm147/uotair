<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("flight_id");
            $table->unsignedBigInteger("flight_class_id");
            //TODO: Add Reference
            $table->text("name");
            $table->string("type");
            $table->string("date_of_birth");
            $table->string("gender");
            $table->string("booking_reference");
            $table->string("email_address");
            $table->double("fare");
            $table->date("departure_date");
            $table->string("seat")->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
