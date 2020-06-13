<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        "name",
        "date_of_birth",
        "type",
        "gender",
        "seat",
        "flight_class_id",
        "flight_id",
        "departure_date",
        "booking_reference",
        "email_address",
        "fare"
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::Class)->with("flight_route");
    }
}
