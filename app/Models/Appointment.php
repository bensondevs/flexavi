<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Znck\Eloquent\Traits\BelongsToThrough;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Appointment\{
    AppointmentType,
    AppointmentStatus,
    AppointmentCancellationVault
};

use App\Enums\Work\WorkStatus;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use PivotEventTrait;
    use HasRelationships;
    use BelongsToThrough;

    protected $table = 'appointments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'cancellation_note',
        'note',
    ];

    protected $fillable = [
        'company_id',
        'customer_id',

        'start',
        'end',
        'include_weekend',

        'previous_appointment_id',
        'next_appointment_id',

        'cancellation_cause',
        'cancellation_vault',
        'cancellation_note',

        'status',
        'type',
        
        'note',
    ];

    protected $casts = [
        'worklists.appointmentables.id' => 'string',

        'include_weekend' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointment) {
            $appointment->id = Uuid::generate()->string;
    	});
    }

    public function scopeCreated(Builder $query)
    {
        return $query->where('status', AppointmentStatus::Created);
    }

    public function scopeCalculated(Builder $query)
    {
        return $query->where('status', AppointmentStatus::Calculated);
    }

    public function setAppointmentStatusAttribute($status)
    {
        if (! is_int($status)) {
            $status = AppointmentStatus::getValue($status);
        }

        $this->attributes['status'] = $status;
    }

    public function setAppointmentTypeAttribute($type)
    {
        if (! is_int($type)) {
            $type = AppointmentType::getValue($type);
        }

        $this->attributes['type'] = $type;
    }

    public function setAppointmentCancellationVaultAttribute($vault)
    {
        if (! is_int($vault)) {
            $vault = AppointmentCancellationVault::getValue($vault);
        }

        $this->attributes['cancellation_vault'] = $vault;
    }

    public function getTypeDescriptionAttribute()
    {
        $typeCode = $this->attributes['type'];
        return AppointmentType::getDescription($typeCode);
    }

    public function getStatusDescriptionAttribute()
    {
        $statusCode = $this->attributes['status'];
        return AppointmentStatus::getDescription($statusCode);
    }

    public function getCancellationVaultDescriptionAttribute()
    {
        $cancellationVaultCode = $this->attributes['cancellation_vault'];
        return AppointmentCancellationVault::getDescription($cancellationVaultCode);
    }

    public function getDurationInDaysAttribute()
    {
        $start = $this->attributes['start'];
        $startDate = carbon()->parse($start)->startOfDay();
        $end = $this->attributes['end'];
        $endDate = carbon()->parse($end)->startOfDay();

        return $startDate->diffInDays($endDate);
    }

    public static function collectAllCancellationVaults()
    {
        return AppointmentCancellationVault::asSelectArray();
    }

    public static function collectAllStatuses()
    {
        return AppointmentStatus::asSelectArray();
    }

    public static function collectAllTypes()
    {
        return AppointmentType::asSelectArray();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function employees()
    {
        return $this->hasManyThrough(
            Employee::class, 
            AppointmentEmployee::class, 
            'appointment_id', 
            'id', 
            'id',
            'employee_id'
        );
    }

    public function subs()
    {
        return $this->hasMany(SubAppointment::class);
    }

    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
    }

    public function finishedWorks()
    {
        return $this->hasMany(Work::class, 'finished_at_appointment_id');
    }

    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    public function warranty()
    {
        return $this->hasOne(Warranty::class);
    }

    public function paymentReminder()
    {
        return $this->hasOne(PaymentReminder::class);
    }

    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    public function revenueables()
    {
        return $this->morphMany(Revenueable::class, 'revenueable');
    }

    public function revenues()
    {
        return $this->morphToMany(Revenue::class, 'revenueable');
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    public function calculation()
    {
        return $this->morphOne(Calculation::class, 'calculationable');
    }

    public function appointmentables()
    {
        return $this->hasMany(Appointmentable::class);
    }

    public function worklists()
    {
        return $this->morphedByMany(Worklist::class, 'appointmentable');
    }

    public function workdays()
    {
        return $this->morphedByMany(Workday::class, 'appointmentable');
    }

    public function warranties()
    {
        return $this->hasMany(Warranty::class);
    }

    public static function typeOptions()
    {
        return AppointmentType::asSelectArray();
    }

    public static function statusOptions()
    {
        return AppointmentStatus::asSelectArray();
    }

    public static function cancellationVaultOptions()
    {
        return AppointmentCancellationVault::asSelectArray();
    }

    public function hasActiveWorks()
    {
        return $this->works()
            ->where('status', WorkStatus::Active)->count() > 0;
    }

    public function hasUnfinishedWorks()
    {
        return $this->works()->where('status', WorkStatus::Unfinished)->count() > 0;
    }

    public function getNumFinishedWorksAttribute()
    {
        return $this->works()->where('status', WorkStatus::Finished)->count();
    }

    public function syncWorkdays()
    {
        $workdays = Workday::inAppointmentRange($this->first())->get();
        return $this->workdays()->sync($workdays);
    }

    public function isLate()
    {
        $end = carbon()->parse($this->attributes['end']);
        $now = carbon()->now();
        return ($now > $end);
    }

    public function isOnTime()
    {
        $end = carbon()->parse($this->attributes['end']);
        $now = carbon()->now();
        return ($now < $end);
    }

    public function execute()
    {
        $this->attributes['status'] = AppointmentStatus::InProcess;
        $this->attributes['in_process_at'] = carbon()->now();
        $execute = $this->save();

        $this->fireModelEvent('executed');

        return $execute;
    }

    public function process()
    {
        $this->attributes['status'] = AppointmentStatus::Processed;
        $this->attributes['processed_at'] = carbon()->now();
        $process = $this->save();

        $this->fireModelEvent('processed');

        return $process;
    }

    public function cancel()
    {
        $this->attributes['status'] = AppointmentStatus::Cancelled;
        $this->attributes['cancelled_at'] = carbon()->now();
        $cancel = $this->save();

        $this->fireModelEvent('cancelled');

        return $cancel;
    }

    public function reschedule(array $rescheduleData)
    {
        $rescheduleAppointment = new self($rescheduleData);
        $rescheduleAppointment->fill([
            'previous_appointment_id' => $this->attributes['id'],
            'company_id' => $this->attributes['company_id'],
            'customer_id' => $this->attributes['customer_id'],
        ]);
        $rescheduleAppointment->save();

        return $rescheduleAppointment;
    }

    public function markCalculated()
    {
        $this->attributes['status'] = AppointmentStatus::Calculated;
        $this->attributes['calculated_at'] = now();
        $calculate = $this->save();

        $this->fireModelEvent('calculated');

        return $calculate;
    }
}