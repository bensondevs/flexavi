<?php

namespace App\Traits;

trait ModelMutators
{
    /**
     * Get model attributes as array with all mutators applied.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        foreach ($this->getMutatedAttributes() as $key) {
            if (!array_key_exists($key, $array)) {
                $array[$key] = $this->{$key};
            }
        }
        return $array;
    }
}
