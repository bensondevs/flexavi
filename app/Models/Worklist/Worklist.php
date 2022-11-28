<?php

namespace App\Models\Worklist;

use App\Enums\Worklist\{WorklistSortingRouteStatus, WorklistStatus};
use App\Models\Appointment\Appointment;
use App\Models\Appointment\Appointmentable;
use App\Models\Appointment\AppointmentEmployee;
use App\Models\Car\Car;
use App\Models\Company\Company;
use App\Models\Cost\Cost;
use App\Models\Receipt\Receipt;
use App\Models\Revenue\Revenue;
use App\Models\User\User;
use App\Models\Workday\Workday;
use App\Observers\WorklistObserver as Observer;
use DateTime;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasManyThrough, HasOneThrough, MorphMany, MorphToMany};
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Worklist extends Model
{

    use HasFactory;
    use SoftDeletes;
    use HasRelationships;
    use \App\Traits\Searchable;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'worklists';

    /**
     * Table name primary key
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
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = ['worklist_name'];

    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'workday_id',
        'status',
        'worklist_name',
        'user_id',
        'sorting_route_status',
        'always_sorting_route_status',
    ];

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
     * Create static-callable `prepared()` method
     * This callable method will query only worklist with status of Prepared
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopePrepared(Builder $query)
    {
        return $query->where('status', WorklistStatus::Prepared);
    }

    /**
     * Create static-callable `processed()` method.
     * This callable method will query only worklist with status of Processed
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeProcessed(Builder $query)
    {
        return $query->where('status', WorklistStatus::Processed);
    }

    /**
     * Create callable "status_description" attribute.
     * This callable attribute will return status description of enum value
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return WorklistStatus::getDescription($status);
    }

    /**
     * Create callable "sorting_route_status_description" attribute.
     * This callable attribute will return status description of enum value
     *
     * @return string
     */
    public function getSortingRouteStatusDescriptionAttribute()
    {
        $status = $this->attributes['sorting_route_status'];

        return WorklistSortingRouteStatus::getDescription($status);
    }

    /**
     * Create callable "always_sorting_route_status_description" attribute.
     * This callable attribute will return status description of enum value
     *
     * @return string
     */
    public function getAlwaysSortingRouteStatusDescriptionAttribute()
    {
        $status = $this->attributes['always_sorting_route_status'];

        return WorklistSortingRouteStatus::getDescription($status);
    }

    /**
     * Create callable "start_time" attribute
     * This callable attribute will return
     * the first attached appointment start time
     *
     * @return DateTime
     */
    public function getStartTimeAttribute()
    {
        if (!$this->appointments->count()) {
            return carbon($this->workday->date);
        }
        $appointment = $this->appointments->sortBy('start')->first();

        return $appointment->start;
    }

    /**
     * Create callable "end_time" attribute
     * This callable attribute will return
     * the last attachedt appointment end time
     *
     * @return DateTime
     */
    public function getEndTimeAttribute()
    {
        if (!$this->appointments->count()) {
            return carbon($this->workday->date);
        }
        $appointment = $this->appointments->sortBy('end')->last();

        return $appointment->end;
    }

    /**
     * Get company of the worklist
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get cars attached to the worklist
     *
     * @return HasOneThrough
     */
    public function car()
    {
        return $this->hasOneThrough(
            Car::class,
            WorklistCar::class,
            'worklist_id',
            'id',
            'id',
            'car_id'
        );
    }

    /**
     * Get workday of the worklist
     *
     * @return BelongsTo
     */
    public function workday()
    {
        return $this->belongsTo(Workday::class);
    }

    /**
     * Get the worklist user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get list of appointments in worklist
     *
     * @return MorphToMany
     */
    public function appointments()
    {
        return $this->morphToMany(
            Appointment::class,
            'appointmentable'
        )->withPivot(['id', 'order_index']);
    }

    /**
     * Get all employees attached to appointments
     * attached to the worklist
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function appointEmployees()
    {
        return $this->hasManyDeep(
            User::class,
            [
                Appointmentable::class,
                Appointment::class,
                AppointmentEmployee::class,
            ],
            [
                ['appointmentable_type', 'appointmentable_id'],
                'id',
                'appointment_id',
                'id',
            ],
            [null, 'appointment_id', 'id', 'user_id']
        );
    }

    /**
     * Get worklist employees
     *
     * @return HasMany
     */
    public function employees()
    {
        return $this->hasMany(WorklistEmployee::class);
    }


    /**
     * Get worklist employees
     *
     * @return HasManyThrough
     */
    public function customEmployees()
    {
        return $this->hasManyThrough(
            User::class,
            WorklistEmployee::class,
            'worklist_id',
            'id',
            'id',
            'user_id'
        );
    }

    /**
     * Get the appointment customers
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function customers()
    {
        return $this->hasManyDeepFromRelations(
            $this->appointments(),
            (new Appointment())->customer()
        );
    }

    /**
     * Get the worklist costs
     *
     * @return MorphToMany
     */
    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    /**
     * Get the worklist costs
     *
     * @return MorphToMany
     */
    public function revenues()
    {
        return $this->morphToMany(Revenue::class, 'revenueable');
    }

    /**
     * Get the worklist receipts
     *
     * @return MorphMany
     */
    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    /**
     * Update the worklist status to processed
     *
     * @return bool
     */
    public function process()
    {
        $this->attributes['status'] = WorklistStatus::Processed;

        return $this->save();
    }

    /**
     * Update the worklist status to calculated
     *
     * @return bool
     */
    public function calculate()
    {
        $this->attributes['status'] = WorklistStatus::Calculated;

        return $this->save();
    }

    /**
     * Collect all possible sorting route status for sorting address
     *
     * @return array
     */
    public static function collectAllStatusesWorklist()
    {
        return WorklistStatus::asSelectArray();
    }
    /**
     * Collect all possible status for worklist
     *
     * @return array
     */
    public static function collectAllStatusesRouteSortingWorlist()
    {
        return WorklistSortingRouteStatus::asSelectArray();
    }
}
