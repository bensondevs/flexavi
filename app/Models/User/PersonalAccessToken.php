<?php

namespace App\Models\User;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Configure model using incrementing ID
     *
     * @var boolean
     */
    public $incrementing = true;

    /**
     * Define the primary key column
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Define the primary key data type
     *
     * @var string
     */
    protected $keyType = 'string';
}
