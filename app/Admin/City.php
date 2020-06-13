<?php


namespace App\Admin;


use App\Admin\Fields\BelongsTo;
use App\Admin\Fields\Text;
use Illuminate\Database\Query\Builder;

class City extends Resource
{
    public static $model = \App\City::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms()->sortable(),
            Text::make("Name", "name")->rules("required")->searchable("cities.name")->sortable(),
            BelongsTo::make("Country", "country","countries")->rules("required")->searchable("countries.name")->sortable("countries.name"),
        ];
    }

    public static function query()
    {
        /** @var Builder $query */
        $query = (static::$model)::with("country");
        $query = $query->leftJoin("countries", "countries.id", "=", "cities.country_id");
        return $query->select("countries.name as country_name", "cities.*");
    }
}
