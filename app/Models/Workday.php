<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\WorkdayObserver as Observer;

use App\Enums\Workday\WorkdayStatus;

class Workday extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasRelationships;

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
    protected $searchable = [
        'date',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'date',
        'status',
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
     * Create static callable `whereCompany(string $id)` method
     * This callable method will query only workday for a certain company
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string  $id
     * @return \Illuminate\Database\Eloquent\Builder
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
    public function scopeInAppointmentRange(Builder $query, Appointment $appointment)
    {
        $start = $appointment->start;
        $end = $appointment->end;

        return $query->whereCompany($appointment->company_id)
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
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get list of worklists of workday
     */
    public function worklists()
    {
        return $this->hasMany(Worklist::class);
    }

    /**
     * Get list of appointments attached to workday
     */
    public function appointments()
    {
        return $this->morphToMany(Appointment::class, 'appointmentable');
    }

    /**
     * Get list of costs attached to workday 
     */
    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    /**
     * Get receipts attached to workday
     */
    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    /**
     * Get costs from worklists under this workday
     */
    public function worklistsCosts()
    {
        return $this->hasManyThrough(Cost::class, Worklist::class);
    }

    /**
     * Get employees attached to this workday
     */
    public function employees()
    {
        return $this->hasManyDeep(
            Employee::class,
            [Appointmentable::class, Appointment::class, AppointmentEmployee::class],
            [['appointmentable_type', 'appointmentable_id'], 'id', 'appointment_id', 'id'],
            [null, 'appointment_id', 'id', 'employee_id']
        );
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