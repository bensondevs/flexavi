<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SubAppointmentObserver;

use App\Enums\SubAppointment\SubAppointmentStatus;
use App\Enums\SubAppointment\SubAppointmentCancellationVault;

class SubAppointment extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'sub_appointments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'appointment_id',

        'previous_sub_appointment_id',
        'rescheduled_sub_appointment_id',

        'status',
        'start',
        'end',

        'cancellation_cause',
        'cancellation_vault',
        'cancellation_note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(SubAppointmentObserver::class);

    	self::creating(function ($subAppointment) {
            $subAppointment->id = Uuid::generate()->string;
    	});
    }

    public static function collectAllCancellationVaults()
    {
        return SubAppointmentStatus::asSelectArray();
    }

    public static function collectAllStatuses()
    {
        return SubAppointmentStatus::asSelectArray();
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return SubAppointmentStatus::getDescription($status);
    }

    public function getCancellationVaultDescriptionAttribute()
    {
        $vault = $this->attributes['cancellation_vault'];
        return SubAppointmentCancellationVault::getDescription($vault);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function previousSubAppointment()
    {
        return $this->belongsTo(self::class, 'previous_sub_appointment_id');
    }

    public function rescheduledSubAppointment()
    {
        return $this->belongsTo(self::class, 'next_sub_appointment_id');
    }

    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
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

    public function cancel(array $cancellationData = [])
    {
        $this->fill($cancellationData);
        $this->attributes['status'] = SubAppointmentStatus::Cancelled;
        $this->attributes['cancelled_at'] = now();
        $cancel = $this->save();

        $this->fireModelEvent('cancel');

        return $cancel;
    }

    public function execute()
    {
        $this->attributes['status'] = SubAppointmentStatus::InProcess;
        $this->attributes['in_process_at'] = now();
        $execute = $this->save();

        $this->fireModelEvent('execute');

        return $execute;
    }

    public function process()
    {
        $this->attributes['status'] = SubAppointmentStatus::Processed;
        $this->attributes['processed_at'] = now();
        $processed = $this->save();

        $this->fireModelEvent('process');

        return $processed;
    }
}