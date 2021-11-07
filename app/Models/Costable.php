<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\CostableObserver;

class Costable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'costables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'company_id',

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    public function costable()
    {
        return $this->morphTo();
    }

    public function appointment()
    {
        return $this->costable()
            ->where('costable_type', Appointment::class);
    }

    public function worklist()
    {
        return $this->costable()
            ->where('costable_type', Worklist::class);
    }

    public function workday()
    {
        return $this->costable()
            ->where('costable_type', Workday::class);
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