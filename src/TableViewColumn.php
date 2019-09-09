<?php

namespace KABBOUCHI\TableView;

use Illuminate\Support\Str;

class TableViewColumn
{
    protected $title;

    private $propertyName;

    private $customValue;

    private $cast = null;

    private $cssClasses = null;

    public function __construct($title, $value, $cast = null, $cssClasses = null)
    {
        $this->cssClasses = $cssClasses;

        if (is_null($value)) {
            $value = $title;
            $title = '';
        }
        $this->title = ($title === false) ? '' : $title;

        if (is_string($value) && $cast == null) {
            $this->propertyName = $value;
        } else {
            $this->cast = $cast;
            $this->customValue = $value;
        }
    }

    public function title()
    {
        return $this->title;
    }

    public function cssClasses()
    {
        return $this->cssClasses;
    }

    public function propertyName()
    {
        return $this->propertyName;
    }

    public function rowValue($model)
    {
        if (! isset($this->customValue)) {
            if (Str::contains($this->propertyName, '.')) {
                try {
                    $keys = explode('.', $this->propertyName);

                    $value = $model;

                    foreach ($keys as $key) {
                        $value = $value->{$key};
                    }

                    return $value;
                } catch (\Exception $exception) {
                    //
                }
            }

            if (is_array($model->{$this->propertyName})) {
                return implode(', ', $model->{$this->propertyName});
            }

            return $model->{$this->propertyName};
        } else {
            $closure = $this->customValue;

            return is_callable($closure) ? $closure($model) : self::getCastedValue($model->{$this->customValue});
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function getCastedValue($value)
    {
        $array = explode('|', $this->cast);

        $type = $array[0];

        $options = count($array) <= 1 ? [] : explode(',', $array[1]);

        switch (strtolower($type)) {
            case 'boolean':

                settype($value, 'boolean');

                if (count($options) < 2) {
                    return is_bool($value) ? ($value ? 'True' : 'False') : $value;
                }

                return is_bool($value) ? ($value ? $options[1] : $options[0]) : $value;

            case 'image':

                if (count($options) < 1) {
                    return '<img src="'.$value.'">';
                }

                if (count($options) < 2) {
                    return "<img src='{$value}' width='{$options[0]}'>";
                }

                $class = count($options) >= 3 ? $options[2] : '';

                return "<img src='{$value}' class='{$class}' width='{$options[0]}' height='{$options[1]}'>";
        }

        return $value;
    }
}
