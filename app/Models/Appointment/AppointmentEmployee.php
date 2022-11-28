<?php

namespace App\Models\Appointment;

use App\Models\User\User;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use Webpatser\Uuid\Uuid;


class AppointmentEmployee extends Model
{

    use HasFactory;


    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'appointment_employees';

    /**
     * The model relations
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * Table name primary key
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
     * @var bool
     */
    protected $fillable = ['appointment_id', 'user_id'];

    /**
     * Function that will be run whenever event happened
     *
     * @return  void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($appointmentEmployee) {
            $appointmentEmployee->id = Uuid::generate()->string;
        });
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['appointment', 'user']);
    }

    /**
     * Check if user is alread attached to the appointment
     *
     * @param Appointment  $appointment
     * @param User  $user
     * @return bool
     */
    public static function isExists(Appointment $appointment, User $user)
    {
        return self::where('appointment_id', $appointment->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get appointment of this pivot model
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get user of this pivot model
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
