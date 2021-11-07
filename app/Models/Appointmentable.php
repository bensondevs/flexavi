<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;

use App\Observers\AppointmentableObserver;

use App\Scopes\IndexOrderedScope;

class Appointmentable extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_index',

        'company_id',

        'appointmentable_id',
        'appointmentable_type',
        'appointment_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::observe(AppointmentableObserver::class);

        self::creating(function ($appointmentable) {
            $appointmentable->id = Uuid::generate()->string;
            $appointmentable->order_index = $appointmentable->setOrderIndex();
        });
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IndexOrderedScope);
    }

    public function scopeSiblingsOf(Builder $query)
    {
        return $query->where('appointmentable_id', $this->attributes['appointmentable_id'])
            ->where('appointmentable_type', $this->attributes['appointmentable_type']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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

    public function setOrderIndex()
    {
        $index = self::siblingsOf($this)->count() + 1;
        return $this->attributes['order_index'] = $index;
    }

    public function moveOrderIndex(int $targetIndex)
    {
        $appointmentables = self::siblingsOf($this)->get();
        $currentIndex = $this->attributes['order_index'];
    }

    public function reorderIndex()
    {
        $appointmentables = self::siblingsOf($this)->get();
        foreach ($appointmentables as $index => $appointmentable) {
            if ($appointmentable->order_index !== ($index + 1)) {
                $appointmentable->order_index++;
                $appointmentable->save();
            }
        }
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
