<?php


namespace App\Admin;


use App\Admin\Fields\BelongsTo;
use App\Admin\Fields\Boolean;
use App\Admin\Fields\OnlineSeating;
use App\Admin\Fields\Text;
use Illuminate\Database\Query\Builder;

class Flight extends Resource
{
    public static $model = \App\Flight::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms(),
            Text::make("Flight Number", "number")->rules("required")->searchable()->sortable(),
            BelongsTo::make("Flight Route", "flight_route", "flight-routes", "title")
                ->searchable("flight_routes.id")
                ->rules("required"),
            Text::make("Departure Time", "departure_time")->rules("required"),
            Text::make("Arrival Time", "arrival_time")->rules("required"),
            Boolean::make("Every Sunday", "sunday"),
            Boolean::make("Every Monday", "monday"),
            Boolean::make("Every Tuesday", "tuesday"),
            Boolean::make("Every Wednesday", "wednesday"),
            Boolean::make("Every Thursday", "thursday"),
            Boolean::make("Every Friday", "friday"),
            Boolean::make("Every Saturday", "saturday"),

            Boolean::make("On Board Wifi", "on_board_wifi"),
            Boolean::make("Provide Meals", "meals"),
            Boolean::make("Seat Display", "seat_display"),
            Boolean::make("Seat Power Plug", "power_plug"),
            OnlineSeating::make("Online Seating", "online_seating")
        ];
    }

    public static function query()
    {
        /** @var Builder $query */
        $query = (static::$model)::with("flight_route","classes");;
        $query = $query->leftJoin("flight_routes", "flight_routes.id", "=", "flights.flight_route_id");
        return $query->select("flights.*", "flight_routes.id as route_title");
    }
}
