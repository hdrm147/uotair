<?php


namespace App\Admin;


use App\Admin\Fields\BelongsTo;
use App\Admin\Fields\Text;
use Illuminate\Database\Query\Builder;

class Airport extends Resource
{
    public static $model = \App\Airport::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms(),
            Text::make("Name", "name")->rules("required")->searchable("airports.name")->sortable(),
            Text::make("Code", "code")->rules("required")->searchable()->sortable(),
            BelongsTo::make("Country", "country", "countries")->searchable("countries.name")->rules("required")->sortable("countries.name"),
            BelongsTo::make("City", "city", "cities")->searchable("cities.name")->rules("required")->sortable("cities.name"),


        ];
    }

    public static function query()
    {
        /** @var Builder $query */
        $query = (static::$model)::with("country","city");
        $query = $query->leftJoin("countries", "countries.id", "=", "airports.country_id");
        $query = $query->leftJoin("cities", "cities.id", "=", "airports.city_id");
        return $query->select("airports.*","cities.name as city_name","countries.name as country_name");
    }
}
