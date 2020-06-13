<?php


namespace App\Admin;


use App\Admin\Fields\ColorPicker;
use App\Admin\Fields\Text;

class FlightClass extends Resource
{
    public static $model = \App\FlightClass::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms()->sortable(),
            Text::make("Name", "name")->rules("required")->sortable()->searchable(),
            ColorPicker::make("Seat Color", "seat_color")->rules("required"),

        ];
    }

    public static function query()
    {
        return (static::$model)::query();
    }
}
