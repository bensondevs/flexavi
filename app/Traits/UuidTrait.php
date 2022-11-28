<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UuidTrait
{
    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->keyType = 'string';
            $model->incrementing = false;
            $model->{$model->getKeyName()} =
                $model->{$model->getKeyName()} ?: (string)Str::orderedUuid();
        });
    }

    /**
     * Get the model-incrementing option
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the model key type
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
