<?php

namespace App\Models\Workday;

use App\Enums\Workday\WorkdayStatus;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Cost\Cost;
use App\Models\Receipt\Receipt;
use App\Models\Work\Work;
use App\Models\Worklist\Worklist;
use App\Observers\WorkdayObserver as Observer;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasManyThrough, MorphMany, MorphToMany};
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Workday extends Model
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
    protected $table = 'workdays';

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
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = ['date'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['company_id', 'date', 'status'];

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
     * Create static callable `whereCompany(string $id)` method
     * This callable method will query only workday for a certain company
     *
     * @param Builder  $query
     * @param string  $id
     * @return Builder
     */
    public function scopeWhereCompany(Builder $query, string $id)
    {
        return $query->where('company_id', $id);
    }

    /**
     * Create static callable `inAppointmentRange(Appointment $appointment)` method
     * This callable static method will query workdays in between start and end of
     * a certain appointment
     */
    public function scopeInAppointmentRange(
        Builder $query,
        Appointment $appointment
    ) {
        $start = $appointment->start;
        $end = $appointment->end;

        return $query
            ->whereCompany($appointment->company_id)
            ->whereBetween('date', [$start, $end]);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will return status description
     * by enum value given in status
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return WorkdayStatus::getDescription($status);
    }

    /**
     * Get workday company
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get list of worklists of workday
     *
     * @return HasMany
     */
    public function worklists()
    {
        return $this->hasMany(Worklist::class);
    }

    /**
     * Get list of appointments attached to workday
     *
     * @return MorphToMany
     */
    public function appointments()
    {
        return $this->morphToMany(Appointment::class, 'appointmentable');
    }

    /**
     * Get list of appointments revenues attached to workday
     *
     * @return HasManyDeep
     */
    public function appointmentsRevenues()
    {
        return $this->hasManyDeepFromRelations(
            $this->appointments(),
            (new Appointment)->revenues()
        );
    }


    /**
     * Get list of appointments works attached to workday
     *
     * @return HasManyDeep
     */
    public function appointmentsWorks()
    {
        return $this->hasManyDeepFromRelations(
            $this->appointments(),
            (new Appointment)->works()
        );
    }

    /**
     * Get list of appointments works revenues attached to workday
     *
     * @return HasManyDeep
     */
    public function appointmentsWorksRevenues()
    {
        return $this->hasManyDeepFromRelations(
            $this->appointmentsWorks(),
            (new Work)->revenue()
        );
    }

    /**
     * Get list of appointments attached to workday
     *
     * @return MorphToMany
     */
    public function subAppointments()
    {
        return $this->hasManyDeepFromRelations(
            $this->appointments(),
            (new Appointment())->subs()
        );
    }

    /**
     * Get list of unplanned appointments attached to workday
     */
    public function unplannedAppointments()
    {
        return $this->appointments()->whereDoesntHave('worklists')->distinct();
    }

    /**
     * Get list of costs attached to workday
     *
     * @return MorphToMany
     */
    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    /**
     * Get receipts attached to workday
     *
     * @return MorphMany
     */
    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    /**
     * Get costs from worklists under this workday
     *
     * @return HasManyThrough
     */
    public function worklistsCosts()
    {
        return $this->hasManyThrough(Cost::class, Worklist::class);
    }

    /**
     * Get employees attached to this workday
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function employees()
    {
        return $this->hasManyDeepFromRelations(
            $this->worklists(),
            (new Worklist())->customEmployees()
        )->distinct();
    }

    /**
     * Get cars attached to this workday
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function cars()
    {
        return $this->hasManyDeepFromRelations(
            $this->worklists(),
            (new Worklist())->car()
        )->distinct();
    }

    /**
     * Get cars attached to this workday
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function customers()
    {
        return $this->hasManyDeepFromRelations(
            $this->worklists(),
            (new Worklist())->customers()
        )->distinct();
    }

    /**
     * Generate certain amount of worklists
     * under current workday
     *
     * @param int $amount
     * @return bool
     */
    public function generateWorklists(int $amount)
    {
        $rawWorklists = [];
        for ($index = 0; $index < $amount; $index++) {
            array_push($rawWorklists, [
                'id' => generateUuid(),
                'company_id' => $this->attributes['company_id'],
                'workday_id' => $this->attributes['id'],
                'status' => \App\Enums\Worklist\WorklistStatus::Prepared,
                'worklist_name' => 'Worklist ' . ($index + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return Worklist::insert($rawWorklists);
    }

    /**
     * Process workday and set the status to be processed
     *
     * @return bool
     */
    public function process()
    {
        $this->attributes['status'] = WorkdayStatus::Processed;
        $this->attributes['processed_at'] = now();

        return $this->save();
    }

    /**
     * Calculate workday and set the status to be calculated
     *
     * @return bool
     */
    public function calculate()
    {
        $this->attributes['status'] = WorkdayStatus::Calculated;
        $this->attributes['calculated_at'] = now();

        return $this->save();
    }
}
