<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Flight extends BaseModel
{
    protected $hidden = ["created_at", "updated_at"];
    protected $fillable = [
        "number",
        "flight_route_id",
        "sunday",
        "monday",
        "tuesday",
        "wednesday",
        "thursday",
        "friday",
        "saturday",
    ];
    protected $casts = [
        "sunday" => "boolean",
        "monday" => "boolean",
        "tuesday" => "boolean",
        "wednesday" => "boolean",
        "thursday" => "boolean",
        "friday" => "boolean",
        "saturday" => "boolean",
        "on_board_wifi" => "boolean",
        "meals" => "boolean",
        "seat_display" => "boolean",
        "power_plug" => "boolean",
        "online_seating" => "json"
    ];


    public function flight_route()
    {
        return $this->belongsTo(FlightRoute::Class);
    }

    public function getFareFor($date)
    {

        $start_price = 139;

        $end_price = 404;

        $every = 7;

        $value = 108;

        $date = Carbon::parse('2020-06-22 11:00:00');

        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        $delta = $diff / $every;

        $price = $end_price - ($delta * $value);


    }

    public function safe_classes()
    {
        return $this->belongsToMany(FlightClass::Class, 'flights_classes', 'flight_id', 'flight_class_id');
    }

    public function classes()
    {
        return $this->safe_classes()->withPivot(["minimum_adult_fare", "maximum_adult_fare", "children_fare_percentage", "fare_increment", "fare_every_days", "capacity", "id", "number_of_bags", "bag_weight_limit"]);
    }


    public function tickets()
    {
        return $this->hasMany(Ticket::Class);
    }
}
