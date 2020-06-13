<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends BaseModel
{
    protected $with = ["city"];
    protected $fillable = [
        "name",
        "code",
        "country_id",
        "city_id",
        "latitude",
        "longitude",
        "timezone",
    ];

    public function city()
    {
        return $this->belongsTo(City::Class);
    }

    public function country()
    {
        return $this->belongsTo(Country::Class);
    }

    public function departure_routes()
    {
        return $this->hasMany(FlightRoute::Class, 'departure_airport_id');
    }

    public function arrival_routes()
    {
        return $this->hasMany(FlightRoute::Class, 'arrival_airport_id');
    }
}
