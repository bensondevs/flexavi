<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

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

    	self::creating(function ($subAppointment) {
            $subAppointment->id = Uuid::generate()->string;
    	});
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return SubAppointmentStatus::getDescription($status);
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
}