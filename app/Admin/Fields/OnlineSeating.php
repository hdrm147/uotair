<?php


namespace App\Admin\Fields;

use App\Flight;

class OnlineSeating extends Field
{
    public $component = "online-seating";

    public function __construct(...$arguments)
    {
        list($text, $attribute) = $arguments;
        $this->text = $text;
        $this->attribute = $attribute;
        $this->creationRules = [function ($attribute, $value, $fail) {
            $value = json_decode($value);
            $grid = collect($value->grid);
            $cells = $grid->map(function ($row) {
                return $row->cells;
            })->flatten();

            $hasNotAssigned= $cells->contains(function($cell){
                return $cell->type == "not-assigned";
            });

            if($hasNotAssigned) {
                $fail("Please assign all seats");
            }

            $hasCellWithoutLetter = $cells->contains(function($cell){
                return $cell->type !== "no-seat" && ($cell->letter == "" || $cell->letter == null);
            });
            if($hasCellWithoutLetter) {
                $fail("Please letters for all the available seats");
            }


            $grouped = $cells->groupBy("type");

            $flight = Flight::with("classes")->find(request("resourceId"));
            foreach ($flight->classes as $flightClass) {
                $group = $grouped->get("class-" . $flightClass->id);
                if ($group === null) {
                    $fail("Please define the $flightClass->name class seats");
                    return;
                }
                if ($flightClass->pivot->capacity > count($group)) {
                    $fail("You have to specify at least " . $flightClass->pivot->capacity . " seats for the $flightClass->name class");
                }
            }
        }];
        $this->updateRules = $this->creationRules;
    }

    public function fill($item)
    {
        $this->value = request($this->attribute);
        if ($this->value || $this->nullable) {
            $item->{$this->attribute} = $this->value ? json_decode($this->value) : null;
        }
    }
}
