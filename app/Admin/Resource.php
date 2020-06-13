<?php


namespace App\Admin;


use App\Admin\Fields\Text;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Str;

abstract class Resource
{
    public static $model;

    /**
     *
     */
    abstract public static function fields();

    public static function indexFields()
    {
        return array_merge(static::fields(), [
            Text::make("Actions", "actions")
        ]);
    }

    public static function detailFields()
    {
        $fields = [];
        foreach (static::fields() as $field) {
            if ($field->showOnDetail) {
                $fields[] = $field;
            }
        };
        return $fields;
    }

    public static function updateFields()
    {
        $fields = [];
        foreach (static::fields() as $field) {
            if ($field->showOnForm) {
                $fields[] = $field;
            }
        };
        return $fields;
    }

    public static function creationFields()
    {
        $fields = [];
        foreach (static::fields() as $field) {
            if ($field->showOnForm) {
                $fields[] = $field;
            }
        };
        return $fields;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return Str::plural(Str::title(Str::snake(class_basename(get_called_class()), ' ')));
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return Str::singular(static::label());
    }

}
