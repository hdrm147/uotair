<?php


namespace App\Admin;


use App\Admin\Fields\BelongsTo;
use App\Admin\Fields\Text;
use Illuminate\Database\Query\Builder;

class FlightRoute extends Resource
{
    public static $model = \App\FlightRoute::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms(),
            BelongsTo::make("Departure Airport", "departure_airport", "airports")->rules("required")->searchable("departure_airport_code"),
            BelongsTo::make("Arrival Airport", "arrival_airport", "airports")->rules("required")->searchable("arrival_airport_code"),
            Text::make("Duration", "duration")->rules("required"),

        ];
    }

    public static function query()
    {
        /** @var Builder $query */
        $query = (static::$model)::with("departure_airport", "arrival_airport");
        $query = $query->leftJoin("airports as departure_airports", "departure_airports.id", "=", "flight_routes.departure_airport_id");
        $query = $query->leftJoin("airports as arrival_airports", "arrival_airports.id", "=", "flight_routes.arrival_airport_id");
        $query = $query->select(["flight_routes.*", "arrival_airports.code as arrival_airport_code", "departure_airports.code as departure_airport_code"]);
        return $query;
    }
}
