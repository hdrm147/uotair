<?php


namespace App\Admin;


use App\Admin\Fields\Avatar;
use App\Admin\Fields\Text;
use Illuminate\Validation\Rule;

class User extends Resource
{
    public static $model = \App\User::class;

    public static function fields()
    {
        return [
            Text::make("ID", "id")->exceptOnForms()->sortable(),
            Avatar::make("Avatar", "avatar")->creationRules("nullable")->sortable()->searchable(),
            Text::make("Name", "name")->rules("required")->sortable()->searchable(),
            Text::make("Email", "email")
                ->sortable()->searchable()
                ->creationRules(['required', 'email', 'max:254', 'unique:users,email'])
                ->updateRules(['required', 'email', 'max:254',Rule::unique('users', 'email')->ignore(request()->route("resourceId")),]),
            Text::make("Password", "password")->creationRules("required")->sortable()->searchable()->isSecure()->onlyOnForms(),

        ];
    }

    public static function query()
    {
        return (static::$model)::query();
    }
}
