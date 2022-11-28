<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait ModelEnums
{
    /**
     * Get the enum attributes
     *
     * @param string $enumName
     * @param string $attribute
     * @return array
     */
    public function getEnumAttribute(string $enumName, $attribute = 'value')
    {
        return $this->getEnum($enumName)->only($attribute);
    }

    /**
     * Get the enum data
     *
     * @param string $enumName
     * @return Collection
     */
    public function getEnum(string $enumName)
    {
        return collect(constant(get_class($this) . '::' . $enumName));
    }

    /**
     * Find enu key by value
     *
     * @param string $enumName
     * @param mixed $value
     * @return mixed
     */
    public function findByValue(string $enumName, $value): mixed
    {
        $options = collect(constant(get_class($this) . '::' . $enumName));
        $options = $options->where('value', $value);
        return $options->first();
    }

    /**
     * Find enum by attribute
     *
     * @param string $enumName
     * @param mixed $key
     * @return mixed
     */
    public function findFromAttributes(string $enumName, mixed $key): mixed
    {
        $options = collect(constant(get_class($this) . '::' . $enumName));
        $results = $options->filter(function ($option) use ($key) {
            return strstr($option['label'], $key) ||
                strstr($option['value'], $key);
        });
        return isset($results[0]) ? $results[0] : $options[0];
    }
}
