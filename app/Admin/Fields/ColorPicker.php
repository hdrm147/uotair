<?php


namespace App\Admin\Fields;


use Illuminate\Support\Facades\Hash;

class ColorPicker extends Field
{
    public $component = "color-picker";

    public function __construct(...$arguments)
    {
        list($text, $attribute) = $arguments;
        $this->text = $text;
        $this->attribute = $attribute;
    }

    public function fill($item)
    {
        $this->value = request($this->attribute);
        if ($this->value || $this->nullable) {
            $item->{$this->attribute} = $this->value;
        }
    }
}
