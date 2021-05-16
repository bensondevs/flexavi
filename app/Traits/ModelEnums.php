<?php

namespace App\Traits;

trait ModelEnums 
{
	public function getEnum(string $enumName)
	{
		return collect(constant(get_class($this) . '::' . $enumName));
	}

	public function getEnumAttribute(string $enumName, $attribute =  'value')
	{
		return $this->getEnum($enumName)->only($attribute);
	}

    public function findByValue(string $enumName, $value)
    {
    	$options = collect(constant(get_class($this) . '::' . $enumName));
    	$options = $options->where('value', $value);
    	return $options->first();
    }

    public function findFromAttributes(string $enumName, $key)
    {
    	$options = collect(constant(get_class($this) . '::' . $enumName));
    	$results = $options->filter(function ($option) use ($key) {
            return (
                strstr($option['label'], $key) || 
                strstr($option['value'], $key)
            );
        });
    	return isset($results[0]) ? 
            $results[0] : $options[0];
    }
}