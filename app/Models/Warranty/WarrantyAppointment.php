<?php

namespace App\Models\Warranty;

use App\Models\Appointment\Appointment;
use App\Models\BelongsTo;
use App\Models\HasMany;
use App\Observers\WarrantyAppointmentObserver as Observer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyAppointment extends Model
{
    use HasFactory;

    protected $with = ['appointment', 'warrantyWorks'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'warranty_appointments';

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
    protected $fillable = [
        'warranty_id',
        'appointment_id'
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Get warranty
     *
     * @return BelongsTo
     */
    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    /**
     * Get appointment
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get warranty works
     *
     * @return HasMany
     */
    public function warrantyWorks()
    {
        return $this->hasMany(WarrantyAppointmentWork::class);
    }
}
