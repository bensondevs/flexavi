<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Observers\CostableObserver;

class Costable extends Model
{
    use SoftDeletes;

    protected $table = 'costables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'cost_id',

        'costable_id',
        'costable_type',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(CostableObserver::class);
    }

    public function scopeWhereType($query, $type)
    {
        return $query->where('costable_type', $type);
    }

    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    public function costable()
    {
        return $this->morphTo();
    }

    public static function isAlreadyAttached(Cost $cost, $costable)
    {
        $costableType = get_class($costable);

        return self::where('cost_id', $cost->id)
            ->where('costable_type', $costableType)
            ->where('costable_id', $costable->id)
            ->count() > 0;
    }
}