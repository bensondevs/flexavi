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

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'appointmentables';

    /**
     * The primary key of the model
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
     * Columns that should be casted
     * 
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'order_index',

        'company_id',

        'appointmentable_id',
        'appointmentable_type',
        'appointment_id',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
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
     * This method will handle scope declaration
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IndexOrderedScope);
    }

    /**
     * Create callable method sublingsOf()
     * This method will query another appointmentable pivot 
     * for certain appointmentable model
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param mixed  $appointmentable
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeSiblingsOf(Builder $query, $appointmentable)
    {
        return $query->where('appointmentable_id', $appointmentable->appointmentable_id)
            ->where('appointmentable_type', $appointmentable->appointmentable_type);
    }

    /**
     * Get appointmentable pivot company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment of the appointment pivot 
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get appointmentable model if its worklist
     */
    public function worklist()
    {
        return $this->morphTo(Worklist::class, 'appointmentable');
    }

    /**
     * Get appointmentable model if its workday
     */
    public function workday()
    {
        return $this->morphTo(Workday::class, 'appointmentable');
    }

    /**
     * Set order index.
     * This function is to set the order index for the appointment
     * For an example to put a certain appointment to first order in worklist
     * 
     * @return int
     */
    public function setOrderIndex()
    {
        $index = self::siblingsOf($this)->count() + 1;
        return $this->attributes['order_index'] = $index;
    }

    /**
     * Move order index to certain index
     * 
     * @param int  $targetIndex
     * @return void
     */
    public function moveOrderIndex(int $targetIndex)
    {
        $appointmentables = self::siblingsOf($this)->get();
        $currentIndex = $this->attributes['order_index'];
    }

    /**
     * Massive reorder index
     * This will fix missing index in between a set of order
     * 
     * @return void
     */
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

    /**
     * Get if a certain appointment is already 
     * attached to a certain appointmentable
     * 
     * @param \App\Models\Appointment  $appointment
     * @param Illuminate\Database\Eloquent\Model|mixed  $appointmentable
     * @return \App\Models\Appointmentable 
     */
    public function isAlreadyAttached(Appointment $appointment, $appointmentable)
    {
        $appointmentableType = get_class($appointmentable);

        return self::where('appointment_id', $appointment->id)
            ->where('appointmentable_type', $appointmentableType)
            ->where('appointmentable_id', $appointmentable->id)
            ->count() > 1;
    }

    /**
     * Attach many appointment to a certain appointmentable
     * 
     * @param Illuminate\Database\Eloquent\Model|mixed  $appointmentable
     * @return bool
     */
    public static function attachMany($appointmentable, $appointmentIds)
    {
        $type = get_class($appointmentable);

        $startIndex = self::siblingsOf($appointmentable)->max('order_index');

        $appointmentables = [];
        foreach ($appointmentIds as $id) {
            array_push($appointmentables, [
                'id' => generateUuid(),
                'order_index' => ++$startIndex,
                'company_id' => $appointmentable->company_id,
                'appointmentable_id' => $appointmentable->id,
                'appointmentable_type' => $type,
                'appointment_id' => $id,
            ]);
        }

        return self::insert($appointmentables);
    }
}
