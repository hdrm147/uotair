<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FlightClass extends BaseModel
{
    public function tickets()
    {
        return $this->hasMany(Ticket::Class);
    }

    protected $hidden = ["created_at", "updated_at"];

    public function getFareForDate($date, $adult = true)
    {
        $start_price = $this->pivot->minimum_adult_fare;

        $end_price = $this->pivot->maximum_adult_fare;;

        $every = $this->pivot->fare_every_days;

        $value = $this->pivot->fare_increment;

        $date = Carbon::createFromFormat('Y-m-d', $date);

        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        $delta = $diff / $every;

        $price = $end_price - ($delta * $value);
        $price = $price < $start_price ? $start_price : $price;
        return $adult ? $price : ($price * ($this->pivot->children_fare_percentage / 100));
    }

}
