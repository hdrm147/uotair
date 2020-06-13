<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlightRoute extends BaseModel
{
    protected $appends = ["title"];
    protected $fillable = [
        "departure_airport_id",
        "arrival_airport_id",
    ];

    protected $with = [
        "departure_airport",
        "arrival_airport",
    ];

    public function getTitleAttribute()
    {
        return $this->departure_airport->city->name . " ({$this->departure_airport->code}) " . " -> " . $this->arrival_airport->city->name . " ({$this->arrival_airport->code})";
    }

    public function departure_airport()
    {
        return $this->belongsTo(Airport::Class, 'departure_airport_id');
    }

    public function arrival_airport()
    {
        return $this->belongsTo(Airport::Class, 'arrival_airport_id');
    }

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

}
