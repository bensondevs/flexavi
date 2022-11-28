<?php

namespace App\Casts;

use App\Enums\Quotation\QuotationDamageCause;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;


class QuotationDamageCausesCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        $damageCauses = json_decode($value, true);
        if (is_string($damageCauses)) {
            $damageCauses = json_decode($value, true);
        }

        foreach ($damageCauses as $key => $cause) {
            $damageCauses[$key] = QuotationDamageCause::getDescription($cause);
        }

        return $damageCauses;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return string|bool
     */
    public function set($model, string $key, $value, array $attributes): string|bool
    {
        return json_encode($value);
    }
}
