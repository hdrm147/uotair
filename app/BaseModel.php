<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{


    public static function skeleton()
    {
        $model = new static;
        $attributes = (object)[];
        foreach ($model->fillable as $attribute) {
            $attributes->{$attribute} = null;
        }
        return $attributes;
    }
}
