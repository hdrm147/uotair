<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlightClassPivot extends BaseModel
{
    protected $table = "flights_classes";
    protected $fillable = [
        "class_id",
        "flight_id",
        "capacity",
        "minimum_adult_fare",
        "maximum_adult_fare",
        "children_fare_percentage",
        "fare_increment",
        "fare_every_days",
        "bag_weight_limit",
        "number_of_bags"
    ];

    public function flight_class()
    {
        return $this->belongsTo(FlightClass::Class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::Class);
    }


}
