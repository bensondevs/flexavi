<?php

namespace App\Traits;

trait ModelEnums 
{
	public function getEnum(string $enumName)
	{
		return collect(self::{$enumName});
	}

	public function getEnumAttribute(string $enumName, $attribute =  'value')
	{
		return $this->getEnum($enumName)->only($attribute);
	}

    public function findByValue(string $enumName, $value)
    {
    	$options = collect(self::{$enumName});
    	$options = $options->where('value', $value)
    	return $options->first();
    }
}