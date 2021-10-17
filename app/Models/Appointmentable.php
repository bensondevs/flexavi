<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Appointmentable extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'appointmentable_id',
        'appointmentable_type',
        'appointment_id',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($appointmentable) {
            $appointmentable->id = Uuid::generate()->string;
        });
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

    public static function attachMany($appointmentable, $appointmentIds)
    {
        $type = get_class($appointmentable);
        $appointmentables = [];
        foreach ($appointmentIds as $id) {
            array_push($appointmentables, [
                'id' => generateUuid(),
                'appointmentable_id' => $appointmentable->id,
                'appointmentable_type' => $type,
                'appointment_id' => $id,
            ]);
        }

        return self::insert($appointmentables);
    }
}
