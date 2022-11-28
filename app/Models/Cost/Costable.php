<?php

namespace App\Models\Cost;

use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use App\Observers\CostableObserver;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo,};


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
     * @param Builder  $query
     * @return Builder
     */
    public function scopeWhereType(Builder $query, string $type)
    {
        return $query->where('costable_type', $type);
    }

    /**
     * Get company model of the costable
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get cost model which connected by this pivot
     *
     * @return BelongsTo
     */
    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    /**
     * Get costable model which connected by this pivot
     * Possible costable models so far are
     * Appointment, Worklist, Workday
     *
     * @return MorphTo
     */
    public function costable()
    {
        return $this->morphTo();
    }

    /**
     * Get the costable as an appointment
     *
     * @return MorphTo
     */
    public function appointment()
    {
        return $this->costable()->where('costable_type', Appointment::class);
    }

    /**
     * Get the costable as a worklist
     *
     * @return MorphTo
     */
    public function worklist()
    {
        return $this->costable()->where('costable_type', Worklist::class);
    }

    /**
     * Get the costable as a workday
     *
     * @return MorphTo
     */
    public function workday()
    {
        return $this->costable()->where('costable_type', Workday::class);
    }

    /**
     * Check is costable is already attached
     *
     * @param Cost $cost
     * @param Model $costable
     * @return bool
     */
    public static function isAlreadyAttached(Cost $cost, Model $costable)
    {
        $costableType = get_class($costable);

        return self::where('cost_id', $cost->id)
            ->where('costable_type', $costableType)
            ->where('costable_id', $costable->id)
            ->count() > 0;
    }
}
