<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\CostableObserver;

class Costable extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'costables';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = true;

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',

        'cost_id',

        'costable_id',
        'costable_type',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(CostableObserver::class);
    }

    /**
     * Create callable method of "whereType(string $type)" to query
     * only costables pivot record which has costable type of requested type
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeWhereType(Builder $query, string $type)
    {
        return $query->where('costable_type', $type);
    }

    /**
     * Get company model of the costable
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get cost model which connected by this pivot
     */
    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    /**
     * Get costable model which connected by this pivot
     * Possible costable models so far are 
     * \App\Models\Appointment, 
     * \App\ModelsWorklist, 
     * \App\Models\Workday
     */
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