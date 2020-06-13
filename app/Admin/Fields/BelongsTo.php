<?php


namespace App\Admin\Fields;


class BelongsTo extends Field
{
    public $component = "belongs-to";
    public $title = "name";
    public $resourceName = "";
    public $options = [];

    public function __construct(...$arguments)
    {
        list($text, $attribute, $resourceName) = $arguments;
        $this->text = $text;
        $this->attribute = $attribute;
        $this->resourceName = $resourceName;
        if (count($arguments) > 3) {
            $this->title = $arguments[3];
        }
    }

    /**
     * @param string $title
     * @return BelongsTo
     */
    public function setTitle(string $title): BelongsTo
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $resourceName
     * @return BelongsTo
     */
    public function setResourceName(string $resourceName): BelongsTo
    {
        $this->resourceName = $resourceName;
        return $this;
    }


    public function fill($item)
    {
        $item->{$this->attribute . "_id"} = $this->value;
    }
}
