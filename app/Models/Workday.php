<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Workday\WorkdayStatus;

class Workday extends Model
{
    use SoftDeletes;
    use Searchable;
    use HasRelationships;

    protected $table = 'workdays';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'date',
    ];

    protected $fillable = [
        'company_id',
        'date',
        'status',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workday) {
            $workday->id = Uuid::generate()->string;
    	});
    }

    public function scopeWhereCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeInAppointmentRange(Appointment $appointment)
    {
        return $query->whereCompany($appointment->company_id)
            ->whereBetween('date', [$appointment->start, $appointment->end]);
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return WorkdayStatus::getDescription($status);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function worklists()
    {
        return $this->hasMany(Worklist::class);
    }

    public function appointments()
    {
        return $this->morphToMany(Appointment::class, 'appointmentable');
    }

    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    public function worklistsCosts()
    {
        return $this->hasManyThrough(Cost::class, Worklist::class);
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

    public function process()
    {
        $this->attributes['status'] = WorkdayStatus::Processed;
        $this->attributes['processed_at'] = now();
        $process = $this->save();

        // $this->fireModelEvent('processed');

        return $process;
    }

    public function calculate()
    {
        // Calculation happens here
        //

        $this->attributes['status'] = WorkdayStatus::Calculated;
        $this->attributes['calculated_at'] = now();
        $calculate = $this->save();

        // $this->fireModelEvent('calculate');

        return $calculate;
    }
}