<?php

namespace App\Models\Work;

use App\Observers\WorkableObserver as Observer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};


class Workable extends Model
{

    use HasFactory;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'workables';

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The primary key type
     *
     * @var string
     */
    protected $keyType = 'string';

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
    public $incrementing = false;

    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = ['workable_type', 'workable_id'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['work_id', 'workable_type', 'workable_id'];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Get work of current pivot model
     *
     * @return BelongsTo
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Get the workable model. Possible workable type
     * \App\Models\Appointment
     * \App\Models\Quotation
     * \App\Models\SubAppointment
     *
     * @return MorphTo
     */
    public function workable()
    {
        return $this->morphTo();
    }

    /**
     * Check if a work is already attached to certain workable model
     *
     * @static
     * @param Work  $work
     * @param mixed  $workable
     * @return bool
     */
    public static function isAlreadyAttached(Work $work, $workable)
    {
        return self::where('work_id', $work->id)
            ->where('workable_type', get_class($workable))
            ->where('workable_id', $workable->id)
            ->exists();
    }
}
