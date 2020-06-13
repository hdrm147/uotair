<?php


namespace App\Admin\Fields;


use Illuminate\Support\Facades\Hash;

class Text extends Field
{
    public $component = "text-field";

    /**
     * Indicates if the field text should be obstructed (password and hashed)
     * @var bool
     */
    public $isSecure = false;

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
            $item->{$this->attribute} = $this->isSecure ? Hash::make($this->value) : $this->value;
        }
    }

}
