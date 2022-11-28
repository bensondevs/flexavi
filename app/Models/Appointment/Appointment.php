<?php

namespace App\Models\Appointment;

use App\Enums\Appointment\{AppointmentCancellationVault as CancellationVault,
    AppointmentStatus,
    AppointmentType as Type
};
use App\Enums\Work\WorkStatus;
use App\Models\Calculation\Calculation;
use App\Models\Company\Company;
use App\Models\Cost\Cost;
use App\Models\Customer\Customer;
use App\Models\ExecuteWork\ExecuteWork;
use App\Models\Inspection\Inspection;
use App\Models\Invoice\Invoice;
use App\Models\PaymentPickup\PaymentPickup;
use App\Models\PaymentPickup\PaymentReminder;
use App\Models\Quotation\Quotation;
use App\Models\Receipt\Receipt;
use App\Models\Revenue\Revenue;
use App\Models\Revenue\Revenueable;
use App\Models\Warranty\Warranty;
use App\Models\Work\Work;
use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use App\Observers\AppointmentObserver;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne, MorphOne, MorphToMany};
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

class Appointment extends Model
{

    use HasFactory;
    use SoftDeletes;
    use PivotEventTrait;
    use HasRelationships;
    use BelongsToThrough;
    use \App\Traits\Searchable;

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
    public $searchableFields = [
        'cancellation_note',
        'note',
        'description'
    ];
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
        'description',
        'cancellation_cause',
        'cancellation_vault',
        'cancellation_note',
        'description',
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
     * Get all possible appointment statuses based on the enum
     *
     * @static
     * @return array
     */
    public static function collectAllStatuses()
    {
        return AppointmentStatus::asSelectArray();
    }

    /**
     * Get all possible appointment types based on the enum
     *
     * @static
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
     * @param Builder $query
     * @param int $status
     */
    public function scopeWhereStatus(Builder $query, int $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Add query only populate appointments with status of Created
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCreated(Builder $query)
    {
        return $query->where('status', AppointmentStatus::Created);
    }

    /**
     * Add query only populate employees with status of Calculated
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCalculated(Builder $query)
    {
        return $query->where('status', AppointmentStatus::Calculated);
    }

    /**
     * Add query only populate payment pickup type of appointments
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePaymentPickupOnly(Builder $query)
    {
        // TODO: implement scopePaymentPickupOnly logic
        return $query;
    }

    /**
     * Set appointment status based on the enum
     *
     * @return void
     */
    public function setAppointmentStatusAttribute($status)
    {
        if (!is_int($status)) {
            $status = AppointmentStatus::getValue($status);
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
        if (!is_int($type)) {
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
        if (!is_int($vault)) {
            $vault = CancellationVault::getValue($vault);
        }
        $this->attributes['cancellation_vault'] = $vault;
    }

    /**
     * Get all possible appointment types based on the enum
     *
     * @return string
     */
    public function getTypeDescriptionAttribute()
    {
        $typeCode = $this->attributes['type'];

        return Type::getDescription($typeCode);
    }

    /**
     * Get all possible appointment statuses based on the enum
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $statusCode = $this->attributes['status'];

        return AppointmentStatus::getDescription($statusCode);
    }

    /**
     * Get all possible appointment cancellation vaults based on the enum
     *
     * @return string
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
        $startDate = carbon()
            ->parse($start)
            ->startOfDay();
        $end = $this->attributes['end'];
        $endDate = carbon()
            ->parse($end)
            ->startOfDay();

        return $startDate->diffInDays($endDate);
    }

    /**
     * Get the company of appointment
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the customer of appointment
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the appointment employees
     *
     * @return HasMany
     */
    public function employees()
    {
        return $this->hasMany(AppointmentEmployee::class);
    }

    /**
     * Get the subs of appointment
     *
     * @return HasMany
     */
    public function subs()
    {
        return $this->hasMany(SubAppointment::class);
    }

    /**
     * Get the company of appointment
     *
     * @return HasOne
     */
    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }

    /**
     * Get the quotation generated from appointment
     *
     * @return HasOne
     */
    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    /**
     * Get the appointment finished works
     *
     * @return HasMany
     */
    public function finishedWorks()
    {
        return $this->hasMany(Work::class, 'finished_at_appointment_id');
    }

    /**
     * Get the company of appointment
     *
     * @return HasMany
     */
    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    /**
     * Get the appointment warranties
     *
     * @return HasMany
     */
    public function warranties()
    {
        return $this->hasMany(Warranty::class);
    }

    /**
     * Get payment pickup happen inside this appointment
     *
     * @return HasOne
     */
    public function paymentPickup()
    {
        return $this->hasOne(PaymentPickup::class);
    }

    /**
     * Get the appointment payment reminder
     *
     * @return HasOne
     */
    public function paymentReminder()
    {
        return $this->hasOne(PaymentReminder::class);
    }

    /**
     * Get the appointment costs
     *
     * @return MorphToMany
     */
    public function costs()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    /**
     * Get the appointment receipts
     *
     * @return MorphToMany
     */
    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }

    /**
     * Get the appointment revenue pivot
     *
     * @return MorphToMany
     */
    public function revenueables()
    {
        return $this->morphMany(Revenueable::class, 'revenueable');
    }

    /**
     * Get the appointment revenues
     *
     * @return MorphToMany
     */
    public function revenues()
    {
        return $this->morphToMany(Revenue::class, 'revenueable');
    }

    /**
     * Get the appointment works revenues
     *
     * @return HasManyDeep
     */
    public function worksRevenues()
    {
        return $this->hasManyDeepFromRelations(
            $this->works(),
            (new Work)->revenue()
        );
    }

    /**
     * Get the appointment works
     *
     * @return MorphToMany
     */
    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
    }

    /**
     * Get the appointment invoice
     *
     * @return MorphOne
     */
    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    /**
     * Get the appointment calculation
     *
     * @return MorphOne
     */
    public function calculation()
    {
        return $this->morphOne(Calculation::class, 'calculationable');
    }

    /**
     * Get the appointment pivot relation with other model
     *
     * @return HasMany
     */
    public function appointmentables()
    {
        return $this->hasMany(Appointmentable::class);
    }

    /**
     * Get the worklists attached to appointment
     *
     * @return MorphToMany
     */
    public function worklists()
    {
        return $this->morphedByMany(Worklist::class, 'appointmentable');
    }

    /**
     * Get related appointments
     */
    public function relatedAppointments()
    {
        return $this->hasManyThrough(
            Appointment::class,
            RelatedAppointment::class,
            'appointment_id',
            'id',
            'id',
            'related_appointment_id'
        );
    }

    /**
     * Check appointment has active works
     *
     * @return bool
     */
    public function hasActiveWorks()
    {
        $status = WorkStatus::Active;

        return $this->works()
            ->where('status', $status)
            ->exists();
    }

    /**
     * Check appointment has unfinished works
     *
     * @return bool
     */
    public function hasUnfinishedWorks()
    {
        $status = WorkStatus::Unfinished;

        return $this->works()
            ->where('status', $status)
            ->exists();
    }

    /**
     * Get appointment amount of finsihed works
     *
     * @return int
     */
    public function getNumFinishedWorksAttribute()
    {
        $status = WorkStatus::Finished;

        return $this->works()
            ->where('status', $status)
            ->count();
    }

    /**
     * Sync workdays passed by the appointment
     *
     * @return bool
     */
    public function syncWorkdays()
    {
        $appointment = self::find($this->attributes['id']);
        $workdays = Workday::inAppointmentRange($appointment)->get();
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
     * Get the worklists attached to appointment
     *
     * @return MorphToMany
     */
    public function workdays()
    {
        return $this->morphedByMany(Workday::class, 'appointmentable');
    }

    /**
     * Execute the appointment and change status to in process
     *
     * @return bool
     */
    public function execute()
    {
        $this->attributes['status'] = AppointmentStatus::InProcess;
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
        $this->attributes['status'] = AppointmentStatus::Processed;
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
        $this->attributes['status'] = AppointmentStatus::Cancelled;
        $this->attributes['cancelled_at'] = carbon()->now();
        $cancel = $this->save();
        $this->fireModelEvent('cancelled');

        return $cancel;
    }

    /**
     * Reschedule appointment
     *
     * @param array $rescheduleData
     * @return Appointment
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

    /**
     * Add related appointments into appointment
     *
     * @return void
     */
    public function attachRelatedAppointments($appointmentIds)
    {
        RelatedAppointment::where('appointment_id', $this->attributes['id'])->delete();

        $rawRelateds = [];

        foreach ($appointmentIds as $appointmentId) {
            array_push($rawRelateds, [
                'id' => generateUuid(),
                'appointment_id' => $this->attributes['id'],
                'related_appointment_id' => $appointmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (count($rawRelateds)) RelatedAppointment::insert($rawRelateds);
    }
}
