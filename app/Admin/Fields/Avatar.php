<?php


namespace App\Admin\Fields;


use Illuminate\Http\UploadedFile;

class Avatar extends Field
{
    public $component = "avatar";

    public function __construct(...$arguments)
    {
        list($text, $attribute) = $arguments;
        $this->text = $text;
        $this->attribute = $attribute;
    }

    public function fill($item)
    {
        /** @var UploadedFile $value */
        if (\request()->hasFile($this->attribute))
            $item->{$this->attribute} = $this->value->store("/avatars", ["disk" => "public"]);
    }
}
