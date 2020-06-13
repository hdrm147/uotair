<?php


namespace App\Admin;


use App\Admin\Fields\BelongsTo;
use App\Admin\Fields\Boolean;
use App\Admin\Fields\Text;

class Country extends Resource
{
    public static $model = \App\Country::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms()->sortable(),
            Text::make("Name", "name")->rules("required")->sortable()->searchable(),

        ];
    }

    public static function query()
    {
        return (static::$model)::query();
    }
}
