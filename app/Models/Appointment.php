<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Znck\Eloquent\Traits\BelongsToThrough;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\AppointmentObserver;

use App\Enums\Appointment\{
    AppointmentType as Type,
    AppointmentStatus as Status,
    AppointmentCancellationVault as CancellationVault
};
use App\Enums\Work\WorkStatus;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use PivotEventTrait;
    use HasRelationships;
    use BelongsToThrough;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'appointments';

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
    protected $searchable = [
        'cancellation_note',
        'note',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',

        'start',
        'end',
        'include_weekend',

        'previous_appointment_id',
        'next_appointment_id',

        'cancellation_cause',
        'cancellation_vault',
        'cancellation_note',

        'status',
        'type',
        
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'worklists.appointmentables.id' => 'string',
        'workdays.appointmentables.id' => 'string',

        'include_weekend' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * Model booting method
     * 
     * @return void
     */
    protected static function booting()
    {
        //
    }

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
        self::observe(AppointmentObserver::class);
    }

    /**
     * Add query only populate appointments with specified Status
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param int  $status
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeWhereStatus(int $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Add query only populate appointments with status of Created
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeCreated(Builder $query)
    {
        return $query->where('status', Status::Created);
    }

    /**
     * Add query only populate employees with status of Calculated
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeCalculated(Builder $query)
    {
        return $query->where('status', Status::Calculated);
    }

    /**
     * Add query only populate payment pickup type of appointments
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopePaymentPickupOnly()
    {
        //
    }

    /**
     * Set appointment status based on the enum
     * 
     * @return void
     */
    public function setAppointmentStatusAttribute($status)
    {
        if (! is_int($status)) {
            $status = Status::getValue($status);
        }

        $this->attributes['status'] = $status;
    }

    /**
     * Set appointment type based on the enum
     * 
     * @return void
     */
    public function setAppointmentTypeAttribute($type)
    {
        if (! is_int($type)) {
            $type = Type::getValue($type);
        }

        $this->attributes['type'] = $type;
    }

    /**
     * Set appointment cancellation vault based on the enum
     * 
     * @return void
     */
    public function setCancellationVaultAttribute($vault)
    {
        if (! is_int($vault)) {
            $vault = CancellationVault::getValue($vault);
        }

        $this->attributes['cancellation_vault'] = $vault;
    }

    /**
     * Get all possible appointment types based on the enum
     * 
     * @return void
     */
    public function getTypeDescriptionAttribute()
    {
        $typeCode = $this->attributes['type'];
        return Type::getDescription($typeCode);
    }

    /**
     * Get all possible appointment statuses based on the enum
     * 
     * @return void
     */
    public function getStatusDescriptionAttribute()
    {
        $statusCode = $this->attributes['status'];
        return Status::getDescription($statusCode);
    }

    /**
     * Get all possible appointment cancellation vaults based on the enum
     * 
     * @return void
     */
    public function getCancellationVaultDescriptionAttribute()
    {
        $cancellationVaultCode = $this->attributes['cancellation_vault'];
        return CancellationVault::getDescription($cancellationVaultCode);
    }

    /**
     * Get duration in day based on start and end of appointment
     * 
     * @return int
     */
    public function getDurationInDaysAttribute()
    {
        $start = $this->attributes['start'];
        $startDate = carbon()->parse($start)->startOfDay();
        $end = $this->attributes['end'];
        $endDate = carbon()->parse($end)->startOfDay();

        return $startDate->diffInDays($endDate);
    }

    /**
     * Get all possible appointment statuses based on the enum
     * 
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Get all possible appointment types based on the enum
     * 
     * @return array
     */
    public static function collectAllTypes()
    {
        return Type::asSelectArray();
    }

    /**
     * Get all possible appointment cancellation vaults based on the enum
     * 
     * @static
     * @return array
     */
    public static function collectAllCancellationVaults()
    {
        return CancellationVault::asSelectArray();
    }

    /**
     * Get the company of appointment
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the company of appointment
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the appointment employees
     */
    public function employees()
    {
        return $this->hasManyThrough(
            Employee::class, 
            AppointmentEmployee::class, 
            'appointment_id', 
            'id', 
            'id',
            'employee_id'
        );
    }

    /**
     * Get the subs of appointment
     */
    public function subs()
    {
        return $this->hasMany(SubAppointment::class);
    }

    /**
     * Get the company of appointment
     */
    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }

    /**
     * Get the quotation generated from appointment
     */
    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    /**
     * Get the appointment works
     */
    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
    }

    /**
     * Get the appointment finished works
     */
    public function finishedWorks()
    {
        return $this->hasMany(Work::class, 'finished_at_appointment_id');
    }

    /**
     * Get the company of appointment
     */
    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    /**
     * Get the appointment warranties
     */
    public function warranties()
    {
        return $this->hasMany(Warranty::class);
    }

    /**
     * Get payment pickup happen inside this appointment
     */
    public function paymentPickup()
    {
        return $this->hasOne(PaymentPickup::class);
    }

    /**
     * Get the appointment payment reminder
     */
    public function paymentReminder()
    {
        return $this->hasOne(PaymentReminder::class);
    }

    /**
     * Get the appointment costs
     */
    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    /**
     * Get the appointment receipts
     */
    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    /**
     * Get the appointment revenue pivot
     */
    public function revenueables()
    {
        return $this->morphMany(Revenueable::class, 'revenueable');
    }

    /**
     * Get the appointment revenues
     */
    public function revenues()
    {
        return $this->morphToMany(Revenue::class, 'revenueable');
    }

    /**
     * Get the appointment invoice
     */
    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    /**
     * Get the appointment calculation
     */
    public function calculation()
    {
        return $this->morphOne(Calculation::class, 'calculationable');
    }

    /**
     * Get the appointment pivot relation with other model
     */
    public function appointmentables()
    {
        return $this->hasMany(Appointmentable::class);
    }

    /**
     * Get the worklists attached to appointment
     */
    public function worklists()
    {
        return $this->morphedByMany(Worklist::class, 'appointmentable');
    }

    /**
     * Get the worklists attached to appointment
     */
    public function workdays()
    {
        return $this->morphedByMany(Workday::class, 'appointmentable');
    }

    /**
     * Check appointment has active works
     * 
     * @return bool
     */
    public function hasActiveWorks()
    {
        $status = WorkStatus::Active;
        return $this->works()->where('status', $status)->exists();
    }

    /**
     * Check appointment has unfinished works
     * 
     * @return bool
     */
    public function hasUnfinishedWorks()
    {
        $status = WorkStatus::Unfinished;
        return $this->works()->where('status', $status)->exists();
    }

    /**
     * Get appointment amount of finsihed works
     * 
     * @return bool
     */
    public function getNumFinishedWorksAttribute()
    {
        $status = WorkStatus::Finished;
        return $this->works()->where('status', $status)->count();
    }

    /**
     * Sync workdays passed by the appointment
     * 
     * @return bool
     */
    public function syncWorkdays()
    {
        $workdays = Workday::inAppointmentRange($this->first())->get();
        $pivots = [];
        foreach ($workdays as $workday) {
            $pivots[$workday->id] = [
                'id' => generateUuid(),
                'company_id' => $workday->company_id,
            ];
        }

        return $this->workdays()->sync($pivots);
    }

    /**
     * Execute the appointment and change status to in process
     * 
     * @return bool
     */
    public function execute()
    {
        $this->attributes['status'] = Status::InProcess;
        $this->attributes['in_process_at'] = carbon()->now();
        $execute = $this->save();

        $this->fireModelEvent('executed');

        return $execute;
    }

    /**
     * Process the appointment and change status to processed
     * 
     * @return bool
     */
    public function process()
    {
        $this->attributes['status'] = Status::Processed;
        $this->attributes['processed_at'] = carbon()->now();
        $process = $this->save();

        $this->fireModelEvent('processed');

        return $process;
    }

    /**
     * Cancel appointment and change status to cancelled
     * 
     * @return bool
     */
    public function cancel()
    {
        $this->attributes['status'] = Status::Cancelled;
        $this->attributes['cancelled_at'] = carbon()->now();
        $cancel = $this->save();

        $this->fireModelEvent('cancelled');

        return $cancel;
    }

    /**
     * Reschedule appointment
     * 
     * @param  array  $rescheduleData
     * @return \App\Models\Appointment  $rescheduleAppointment
     */
    public function reschedule(array $rescheduleData)
    {
        $rescheduleAppointment = new self($rescheduleData);
        $rescheduleAppointment->fill([
            'previous_appointment_id' => $this->attributes['id'],
            'company_id' => $this->attributes['company_id'],
            'customer_id' => $this->attributes['customer_id'],
        ]);
        $rescheduleAppointment->save();

        $this->attributes['next_appointment_id'] = $rescheduleAppointment->id;

        return $rescheduleAppointment;
    }

    /**
     * Mark appointment as calculated and change its status
     * 
     * @return bool
     */
    public function markCalculated()
    {
        $this->attributes['status'] = AppointmentStatus::Calculated;
        $this->attributes['calculated_at'] = now();
        $calculate = $this->save();

        $this->fireModelEvent('calculated');

        return $calculate;
    }
}