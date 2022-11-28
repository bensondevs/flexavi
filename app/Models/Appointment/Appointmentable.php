<?php

namespace App\Models\Appointment;

use App\Models\Company\Company;
use App\Models\Illuminate;
use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use App\Observers\AppointmentableObserver;
use App\Scopes\IndexOrderedScope;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};


class Appointmentable extends Model
{

    use HasFactory;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'appointmentables';

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
    public $incrementing = false;

    /**
     * Columns that should be casted
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'order_index',
        'company_id',
        'appointmentable_id',
        'appointmentable_type',
        'appointment_id',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        self::observe(AppointmentableObserver::class);
    }

    /**
     * The "booted" method of the model.
     * This method will handle scope declaration
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IndexOrderedScope());
    }

    /**
     * Create callable method siblingsOf()
     * This method will query another appointmentable pivot
     * for certain appointmentable model
     *
     * @param Builder  $query
     * @param mixed  $appointmentable
     * @return Builder
     */
    public function scopeSiblingsOf(Builder $query, $appointmentable)
    {
        return $query
            ->where('appointmentable_id', $appointmentable->appointmentable_id)
            ->where(
                'appointmentable_type',
                $appointmentable->appointmentable_type
            );
    }

    /**
     * Get appointmentable pivot company
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment of the appointment pivot
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get appointmentable model if its worklist
     *
     * @return MorphTo
     */
    public function worklist()
    {
        return $this->morphTo(Worklist::class, 'appointmentable');
    }

    /**
     * Get appointmentable model if its workday
     *
     * @return MorphTo
     */
    public function workday()
    {
        return $this->morphTo(Workday::class, 'appointmentable');
    }

    /**
     * Get the appointmentable model
     *
     * @return MorphTo
     */
    public function appointmentable()
    {
        return $this->morphTo();
    }

    /**
     * Set order index.
     * This function is to set the order index for the appointment
     * For an example to put a certain appointment to first order in worklist
     *
     * @return int
     */
    public function setOrderIndex()
    {
        $index = self::siblingsOf($this)->count() + 1;

        return $this->attributes['order_index'] = $index;
    }

    /**
     * Move order index to certain index
     *
     * @param int  $targetIndex
     * @return void
     */
    public function moveOrderIndex(int $targetIndex)
    {
        // TODO: complete moveOrderIndex logic
        $appointmentables = self::siblingsOf($this)->get();
        $currentIndex = $this->attributes['order_index'];
    }

    /**
     * Massive reorder index
     * This will fix missing index in between a set of order
     *
     * @return void
     */
    public function reorderIndex()
    {
        $appointmentables = self::siblingsOf($this)->get();
        foreach ($appointmentables as $index => $appointmentable) {
            if ($appointmentable->order_index !== $index + 1) {
                $appointmentable->order_index++;
                $appointmentable->save();
            }
        }
    }

    /**
     * Get if a certain appointment is already
     * attached to a certain appointmentable
     *
     * @param Appointment  $appointment
     * @param Illuminate\Database\Eloquent\Model|mixed  $appointmentable
     * @return bool
     */
    public static function isAlreadyAttached(
        Appointment $appointment,
        $appointmentable
    ) {
        $appointmentableType = get_class($appointmentable);

        return self::where('appointment_id', $appointment->id)
            ->where('appointmentable_type', $appointmentableType)
            ->where('appointmentable_id', $appointmentable->id)
            ->count() > 1;
    }

    /**
     * Attach many appointment to a certain appointmentable
     *
     * @param Model  $appointmentable
     * @return bool
     */
    public static function attachMany(Model $appointmentable, $appointmentIds)
    {
        $type = get_class($appointmentable);
        $startIndex = self::siblingsOf($appointmentable)->max('order_index');
        $appointmentables = [];
        foreach ($appointmentIds as $id) {
            array_push($appointmentables, [
                'id' => generateUuid(),
                'order_index' => ++$startIndex,
                'company_id' => $appointmentable->company_id,
                'appointmentable_id' => $appointmentable->id,
                'appointmentable_type' => $type,
                'appointment_id' => $id,
            ]);
        }

        return self::insert($appointmentables);
    }
}
