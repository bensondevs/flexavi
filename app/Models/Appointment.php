<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Appointment\AppointmentType;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentCancellationVault;

class Appointment extends Model
{
    use SoftDeletes;
    use Searchable;
    
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
        'include_weekend' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',

        'status' => AppointmentStatus::class,
        'type' => AppointmentType::class,
        'cancellation_vault' => AppointmentCancellationVault::class,
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointment) {
            $appointment->id = Uuid::generate()->string;
    	});
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
        return $this->hasMany(Work::class);
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
        return $this->hasMany(AppointmentCost::class);
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class);
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
        return $this->save();
    }

    public function process()
    {
        $this->attributes['status'] = AppointmentStatus::Processed;
        $this->attributes['processed_at'] = carbon()->now();
        return $this->save();
    }

    public function cancel()
    {
        $this->attributes['status'] = AppointmentStatus::Cancelled;
        $this->attributes['cancelled_at'] = carbon()->now();
        return $this->save();
    }
}