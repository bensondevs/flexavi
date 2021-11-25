<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\SubAppointmentObserver;

use App\Enums\SubAppointment\{
    SubAppointmentStatus as Status,
    SubAppointmentCancellationVault as CancellationVault
};

class SubAppointment extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'sub_appointments';

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
     * Set which columns are mass fillable
     * 
     * @var array
     */
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
        self::observe(SubAppointmentObserver::class);

    	self::creating(function ($subAppointment) {
            $subAppointment->id = Uuid::generate()->string;
    	});
    }

    /**
     * Collect all possible sub appointment cancellation vaults
     * 
     * @return array
     */
    public static function collectAllCancellationVaults()
    {
        return Status::asSelectArray();
    }

    /**
     * Collect all possible sub appointment statuses
     * 
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return status enum description
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Create callable "cancellation_vault_description" attribute
     * This callable attribute will return cancellation vault 
     * enum description
     * 
     * @return string
     */
    public function getCancellationVaultDescriptionAttribute()
    {
        $vault = $this->attributes['cancellation_vault'];
        return CancellationVault::getDescription($vault);
    }

    /**
     * Get appointment parent of this sub appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get continued previous sub appointment
     */
    public function previousSubAppointment()
    {
        return $this->belongsTo(self::class, 'previous_sub_appointment_id');
    }

    /**
     * Rescheduled sub appointment by this sub appointment 
     */
    public function rescheduledSubAppointment()
    {
        return $this->belongsTo(self::class, 'next_sub_appointment_id');
    }

    /**
     * Get company parent of sub appointment
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get sub appointment works
     */
    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
    }

    /**
     * Cancel sub appointment
     * 
     * @param array  $cancellationData
     * @return bool
     */
    public function cancel(array $cancellationData = [])
    {
        $this->fill($cancellationData);
        $this->attributes['status'] = Status::Cancelled;
        $this->attributes['cancelled_at'] = now();
        $cancel = $this->save();

        $this->fireModelEvent('cancel');

        return $cancel;
    }

    /**
     * Execute sub appointment
     * 
     * @return bool
     */
    public function execute()
    {
        $this->attributes['status'] = Status::InProcess;
        $this->attributes['in_process_at'] = now();
        $execute = $this->save();

        $this->fireModelEvent('execute');

        return $execute;
    }

    /**
     * Process sub appointment
     * 
     * @return bool
     */
    public function process()
    {
        $this->attributes['status'] = Status::Processed;
        $this->attributes['processed_at'] = now();
        $processed = $this->save();

        $this->fireModelEvent('process');

        return $processed;
    }
}