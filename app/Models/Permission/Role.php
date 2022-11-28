<?php

namespace App\Models\Permission;

use App\Traits\UuidTrait;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use UuidTrait;

    /**
     * The table name
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The ID key type
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];
}
