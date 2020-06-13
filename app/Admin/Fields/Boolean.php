<?php


namespace App\Admin\Fields;


class Boolean extends Field
{
    public $component = "boolean";
    public $value = false;

    public function __construct(...$arguments)
    {
        list($text, $attribute) = $arguments;
        $this->text = $text;
        $this->attribute = $attribute;
    }

    public function fill($item)
    {
        $this->value = filter_var(request($this->attribute), FILTER_VALIDATE_BOOLEAN);
        $item->{$this->attribute} = $this->value;
    }
}
