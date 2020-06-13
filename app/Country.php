<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{

    protected $fillable = [
        "name",
    ];
    public function cities()
    {
        return $this->hasMany(City::Class);
    }

}
