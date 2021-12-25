<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Worklist\WorklistStatus;

class Worklist extends Model
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
    protected $searchable = [
        'worklist_name',
    ];

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

    	self::creating(function ($worklist) {
            $worklist->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create static-callable `prepared()` method
     * This callable method will query only worklist with status of Prepared
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrepared(Builder $query)
    {
        return $query->where('status', WorklistStatus::Prepared);
    }

    /**
     * Create static-callable `processed()` method.
     * This callable method will query only worklist with status of Processed
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
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
     * Create callable "start_time" attribute
     * This callable attribute will return 
     * the first attached appointment start time
     * 
     * @return \DateTime
     */
    public function getStartTimeAttribute()
    {
        if (! $this->appointments->count()) {
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
     * @return \DateTime
     */
    public function getEndTimeAttribute()
    {
        if (! $this->appointments->count()) {
            return carbon($this->workday->date);
        }

        $appointment = $this->appointments->sortBy('end')->last();
        return $appointment->end;
    }

    /**
     * Get company of the worklist
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get cars attached to the worklist
     */
    public function worklistCars()
    {
        return $this->hasMany(WorklistCar::class);
    }

    /**
     * Get workday of the worklist
     */
    public function workday()
    {
        return $this->belongsTo(Workday::class);
    }

    /**
     * Get list of appointments in worklist
     */
    public function appointments()
    {
        return $this->morphToMany(Appointment::class, 'appointmentable');
    }

    /**
     * Get all employees attached to appointments 
     * attached to the worklist
     */
    public function appointEmployees()
    {
        return $this->hasManyDeep(
            AppointmentEmployee::class,
            [Appointmentable::class, Appointment::class],
            [['appointmentable_type', 'appointmentable_id'], 'id'],
            [null, 'appointment_id']
        );
    }

    /**
     * Get worklist employees
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

    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    public function process()
    {
        $this->attributes['status'] = WorklistStatus::Processed;
        return $this->save();
    }

    public function calculate()
    {
        // Do calculations...

        $this->attributes['status'] = WorklistStatus::Calculated;
        return $this->save();
    }
}