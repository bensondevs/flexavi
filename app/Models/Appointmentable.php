<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Appointmentable extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    public static function boot()
    {
        parent::boot();
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function worklist()
    {
        return $this->morphTo(Worklist::class, 'appointmentable');
    }

    public function workday()
    {
        return $this->morphTo(Workday::class, 'appointmentable');
    }

    public function isAlreadyAttached(Appointment $appointment, $appointmentable)
    {
        $appointmentableType = get_class($appointmentable);

        return self::where('appointment_id', $appointment->id)
            ->where('appointmentable_type', $appointmentableType)
            ->where('appointmentable_id', $appointmentable->id)
            ->count() > 1;
    }
}
