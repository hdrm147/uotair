<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends BaseModel
{
    protected $fillable = [
        "name",
        "country_id",
    ];
    public function country()
    {
        return $this->belongsTo(Country::Class);
    }
    public function airports(){
        return $this->hasMany(Airport::Class);
    }
}
