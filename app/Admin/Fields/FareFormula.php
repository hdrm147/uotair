<?php


namespace App\Admin\Fields;


use Illuminate\Support\Facades\Hash;

class FareFormula extends Field
{
    public $component = "fare-formula";
    public $everyDays;
    public $increment;

    public function __construct(...$arguments)
    {
        list($text, $attribute) = $arguments;
        $this->text = $text;
        $this->attribute = $attribute;
        $this->creationRules = ["required",function ($attribute, $value, $fail) {
            $value = json_decode($value);
            if(!$value->everyDays && !$value->increment) {
                $fail("Fare formula is required");
            }
            if (!is_numeric($value->everyDays) || !is_numeric($value->increment)) {
                $fail('Must be a number');
            }


        }];
        $this->updateRules = $this->creationRules;
    }

    public function fill($item)
    {
        $this->value = request($this->attribute);
        if ($this->value || $this->nullable) {
            $value = json_decode($this->value);

            $item->fare_increment = $value->increment;
            $item->fare_every_days = $value->everyDays;
         }
    }

}
