<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

use App\Enums\Worklist\WorklistStatus;

class Worklist extends Model
{
    use SoftDeletes;
    use Searchable;
    use HasRelationships;

    protected $table = 'worklists';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'worklist_name',
    ];

    protected $fillable = [
        'company_id',
        'workday_id',
        'status',
        'worklist_name',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($worklist) {
            $worklist->id = Uuid::generate()->string;
    	});
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return WorklistStatus::getDescription($status);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function worklistCars()
    {
        return $this->hasMany(WorklistCar::class);
    }

    public function workday()
    {
        return $this->belongsTo(Workday::class);
    }

    public function appointments()
    {
        return $this->morphToMany(Appointment::class, 'appointmentable');
    }

    public function appointEmployees()
    {
        return $this->hasManyDeep(
            AppointmentEmployee::class,
            [Appointmentable::class, Appointment::class],
            [['appointmentable_type', 'appointmentable_id'], 'id'],
            [null, 'appointment_id']
        );
    }

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