<?php

namespace App\Models\Appointment;

use App\Observers\RelatedAppointmentObserver as Observer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RelatedAppointment
 *
 * @property string $id
 * @property string|null $appointment_id
 * @property string|null $related_appointment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Appointment\Appointment|null $appointment
 * @property-read \App\Models\Appointment\Appointment|null $relatedAppointment
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment newQuery()
 * @method static \Illuminate\Database\Query\Builder|RelatedAppointment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment whereRelatedAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedAppointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RelatedAppointment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RelatedAppointment withoutTrashed()
 * @mixin \Eloquent
 */
class RelatedAppointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'related_appointments';

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
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = [];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id',
        'related_appointment_id'
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Get the appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the related appointment
     */
    public function relatedAppointment()
    {
        return $this->belongsTo(Appointment::class, 'related_appointment_id', 'id');
    }
}
