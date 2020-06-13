<?php


namespace App\Admin\Fields;


use Illuminate\Support\Facades\Hash;

class Field
{

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * Indicates if the element should be shown on the detail view.
     *
     * @var bool
     */
    public $showOnDetail = true;

    /**
     * Indicates if the element should be shown on the form view.
     *
     * @var bool
     */
    public $showOnForm = true;

    /**
     * Indicates if the field should be disabled or not.
     *
     * @var bool
     */
    public $disabled = false;


    /**
     * Storing the field value
     * @var null
     */
    public $value = null;

    /**
     * Holds the field's error messages
     * @var array
     */
    public $errorMessages = [];

    /**
     * Search for column name
     * @var string
     */
    public $searchColumn;

    /**
     * Sort for column name
     * @var string
     */
    public $sortColumn;

    /**
     * Indicates if the field should be searchable or not
     * @var bool
     */
    public $searchable = false;

    /**
     * Indicates if the field should be sortable or not
     * @var bool
     */
    public $sortable = false;

    /**
     * Indicates if the field can be null
     * @var bool
     */
    public $nullable = false;
    /**
     * The field text (for header)
     *
     * @var string
     */
    public $text;


    /**
     * The field's component.
     *
     * @var string
     */
    public $component;

    /**
     * The validation rules for creation and updates.
     *
     * @var array
     */
    public $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array
     */
    public $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array
     */
    public $updateRules = [];


    /**
     * The attribute / column name of the field.
     *
     * @var string
     */
    public $attribute;

    /**
     * Create a new element.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    public function exceptOnForms()
    {
        $this->showOnDetail = true;
        $this->showOnIndex = true;
        $this->showOnForm = false;
        return $this;
    }

    public function onlyOnForms()
    {
        $this->showOnDetail = false;
        $this->showOnIndex = false;
        $this->showOnForm = true;
        return $this;
    }

    public function rules($rules)
    {
        if (is_array($rules)) {
            $this->creationRules = $rules;
            $this->updateRules = $rules;
            return $this;
        }
        $this->creationRules = explode("|", $rules);
        $this->updateRules = explode("|", $rules);
        return $this;
    }

    /**
     * @param mixed $searchable
     * @return Field
     */
    public function searchable($searchable = true)
    {
        if (is_string($searchable))
            $this->setSearchColumn($searchable);

        $this->searchable = $searchable;
        return $this;
    }

    /**
     * @param mixed $sortable
     * @return Field
     */
    public function sortable($sortable = true): Field
    {
        if (is_string($sortable))
            $this->setSortColumn($sortable);

        $this->sortable = $sortable;
        return $this;
    }

    /**
     * Getter for search column field
     * @return string
     */
    public function searchColumn()
    {
        if ($this->searchColumn) {
            return $this->searchColumn;
        }
        return $this->attribute;
    }

    /**
     * Getter for sort column field
     * @return string
     */
    public function sortColumn()
    {
        if ($this->sortColumn) {
            return $this->sortColumn;
        }
        return $this->attribute;
    }

    /**
     * @param mixed $searchColumn
     * @return Field
     */
    public function setSearchColumn($searchColumn)
    {
        $this->searchColumn = $searchColumn;
        return $this;
    }


    /**
     * @param mixed $sortColumn
     * @return Field
     */
    public function setSortColumn($sortColumn)
    {
        $this->sortColumn = $sortColumn;
        return $this;
    }

    /**
     * @param $updateRules
     * @return Field
     */
    public function updateRules($updateRules): Field
    {
        if (is_array($updateRules)) {
            $this->updateRules = $updateRules;
            return $this;
        }
        $this->updateRules = explode("|", $updateRules);
        return $this;
    }

    /**
     * @param $creationRules
     * @return Field
     */
    public function creationRules($creationRules): Field
    {
        if (is_array($creationRules)) {
            $this->creationRules = $creationRules;
            return $this;
        }
        $this->creationRules = explode("|", $creationRules);
        return $this;
    }

    /**
     * @param mixed $nullable
     * @return Field
     */
    public function nullable($nullable = true)
    {
        $this->nullable = $nullable;
        return $this;
    }


    /**
     * @param bool $isSecure
     * @return Field
     */
    public function isSecure(bool $isSecure = true): Field
    {
        $this->isSecure = $isSecure;
        return $this;
    }


    /**
     * fill the resource with the field's value
     * @param $item
     */
    public function fill($item)
    {
        $item->{$this->attribute} = request($this->attribute);
    }

}
