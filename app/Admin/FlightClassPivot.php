<?php


namespace App\Admin;


use App\Admin\Fields\BelongsTo;
use App\Admin\Fields\FareFormula;
use App\Admin\Fields\Text;

class FlightClassPivot extends Resource
{

    public static $model = \App\FlightClassPivot::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms()->sortable(),
            BelongsTo::make("Flight", "flight", "flights", "number")
                ->searchable("flights.code")
                ->rules("required"),
            BelongsTo::make("Class", "flight_class", "flight-classes", "name")
                ->searchable("flight_classes.name")
                ->rules(["required", 'unique:flights_classes,flight_class_id,' . request()->route('resourceId') . ',id,flight_id,' . request("flight")]),
            Text::make("Minimum Adult Fare", "minimum_adult_fare")->rules("required|numeric"),
            Text::make("Maximum Adult Fare", "maximum_adult_fare")->rules("required|numeric"),
            Text::make("Children Fare %", "children_fare_percentage")->rules("required|numeric|min:1|max:100"),
            Text::make("Capacity", "capacity")->rules("required|numeric"),
            Text::make("Number Of Bags", "number_of_bags")->rules("required|numeric"),
            Text::make("Bag Weight Limit (Kg)", "bag_weight_limit")->rules("required|numeric"),
            FareFormula::make("Fare Formula", "fare_formula"),
        ];
    }



    public static function query()
    {
        return (static::$model)::with("flight", "flight_class");
    }
}
