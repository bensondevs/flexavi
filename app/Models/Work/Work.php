<?php

namespace App\Models\Work;

use App\Enums\ExecuteWork\ExecuteWorkStatus;
use App\Enums\Work\WorkStatus;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\ExecuteWork\ExecuteWork;
use App\Models\Inspection\InspectionPicture;
use App\Models\Invoice\Invoice;
use App\Models\Quotation\Quotation;
use App\Models\Revenue\Revenueable;
use App\Models\WorkService\WorkService;
use App\Observers\WorkObserver;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne, MorphOne, MorphToMany};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Work extends Model implements HasMedia
{

    use HasFactory;

    use SoftDeletes;
    use HasRelationships;
    use PivotEventTrait;
    use InteractsWithMedia;

    /**
     * Autoload relation
     *
     * @var array
     */
    protected $with = ['workService'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'works';

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
    public $searchableFields = [
        'quantity_unit',
        'description',
        'note',
        'unfinish_note',
        'finish_note',
    ];

    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'status',
        'quantity',
        'quantity_unit',
        'work_service_id',
        'description',
        'unit_price',
        'total_price',
        'finished_at_appointment_id',
        'revenue_recorded',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'include_tax' => 'boolean',
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
        self::observe(WorkObserver::class);
    }

    /**
     * Create callable `onlyStatus(int $status)` method
     * This callable method will query only work with certain
     * requested status based on the status given
     *
     * @param Builder  $query
     * @param int  $status
     * @return Builder
     */
    public function scopeOnlyStatus(Builder $query, int $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Create static callable `finishedAt(Appointment $appointment)` method
     * This static callable method will query only work that finished at the
     * specified appointment.
     *
     * @param Builder  $query
     * @param Appointment  $appointment
     * @return Builder
     */
    public function scopeFinishedAt(Builder $query, Appointment $appointment)
    {
        return $query->where('finished_at_appointment_id', $appointment->id);
    }

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return the enum description
     * of the work status
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return WorkStatus::getDescription($status);
    }

    /**
     * Create callable "unit_total" attribute
     * This callable attribute will return the unit total of the work
     *
     * @return float
     */
    public function getUnitTotalAttribute()
    {
        $quantity = $this->attributes['quantity'];
        $unitPrice = $this->attributes['unit_price'];

        return $quantity * $unitPrice;
    }

    /**
     * Create callable "formatted_unit_total" attribute
     * This callable attribute will return the currency formatted
     * unit total of the work
     *
     * @return string
     */
    public function getFormattedUnitTotalAttribute()
    {
        $unitTotal = $this->getUnitTotalAttribute();

        return currency_format($unitTotal);
    }

    /**
     * Create callable "formatted_unit_price" attribute
     * This callable attribute will return the currency formatted
     * unit price of the work
     *
     * @return string
     */
    public function getFormattedUnitPriceAttribute()
    {
        return currency_format($this->attributes['unit_price']);
    }

    /**
     * Create callable "formatted_tax_percentage" attribute
     * This attribute will return percentage format of tax
     *
     * @return string
     */
    public function getFormattedTaxPercentageAttribute()
    {
        $percentage = $this->attributes['tax_percentage'];

        return $percentage . '%';
    }

    /**
     * Create callable "tax_amount" attribute
     * This attribute will return the amount of tax that
     * will be added to the total
     *
     * @return float
     */
    public function getTaxAmountAttribute()
    {
        $unitTotal = $this->getUnitTotalAttribute();
        $taxPercentage = $this->attributes['tax_percentage'];

        return $unitTotal * ($taxPercentage / 100);
    }

    /**
     * Get the formatted tax amount
     *
     * @return string
     */
    public function getFormattedTaxAmountAttribute()
    {
        $taxAmount = $this->getTaxAmountAttribute();

        return currency_format($taxAmount);
    }

    /**
     * Get the formatted total price
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute()
    {
        $totalPrice = $this->attributes['total_price'];

        return currency_format($totalPrice);
    }

    /**
     * Get the formatted total paid
     *
     * @return string
     */
    public function getFormattedTotalPaidAttribute()
    {
        $totalPaid = $this->attributes['total_paid'];

        return currency_format($totalPaid);
    }

    /**
     * Count the total price of the work
     * The total is acquired by multiplication of quantity and unit_price
     *
     * @return float
     */
    public function countTotalPrice()
    {
        $quantity = $this->attributes['quantity'];
        $unitPrice = $this->attributes['unit_price'];
        $total = $quantity * $unitPrice;

        return $this->attributes['total_price'] = $total;
    }

    /**
     * Get work company
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get work service
     *
     * @return BelongsTo
     */
    public function workService()
    {
        return $this->belongsTo(WorkService::class);
    }

    /**
     * Get invioces of the work
     *
     * @return MorphToMany
     */
    public function invoices()
    {
        return $this->morphedByMany(Invoice::class, 'workable');
    }

    /**
     * Get quotations of the work
     *
     * @return MorphToMany
     */
    public function quotations()
    {
        return $this->morphedByMany(Quotation::class, 'workable');
    }

    /**
     * Get inspection Picture of the work
     *
     * @return MorphToMany
     */
    public function inspectionPictures()
    {
        return $this->morphedByMany(InspectionPicture::class, 'workable');
    }

    /**
     * Create callable attribute of "quotation"
     * This callable attribute will return one quotation
     *
     * @return Quotation|null
     */
    public function getQuotationAttribute()
    {
        return $this->quotations->first();
    }

    /**
     * Get appointments of work
     *
     * @return MorphToMany
     */
    public function appointments()
    {
        return $this->morphedByMany(Appointment::class, 'workable');
    }

    /**
     * Create callable attribute of "appointment"
     * This callable attribute will return one appointment
     *
     * @return Appointment|null
     */
    public function getAppointmentAttribute()
    {
        return $this->appointments->first();
    }

    /**
     * Get appointment where work is finished
     *
     * @return BelongsTo
     */
    public function finishedAtAppointment()
    {
        return $this->belongsTo(
            Appointment::class,
            'finished_at_appointment_id',
            'id'
        );
    }

    /**
     * Get the execute work log
     *
     * @return HasMany
     */
    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    /**
     * Get current execution of work
     *
     * @return HasOne
     */
    public function currentExecuteWork()
    {
        return $this->hasOne(ExecuteWork::class)->where(
            'status',
            ExecuteWorkStatus::InProcess
        );
    }

    /**
     * Get revenueable type of this work
     *
     * @return MorphOne
     */
    public function revenueable()
    {
        return $this->morphOne(
            Revenueable::class,
            'revenueable'
        )->oldestOfMany();
    }

    /**
     * Get revenue of this work
     *
     * @return HasOneDeep
     */
    public function revenue()
    {
        return $this->hasOneDeepFromRelations(
            $this->revenueable(),
            (new Revenueable)->revenue()
        );
    }

    /**
     * Collect all possible statuses of work
     *
     * @static
     * @return array
     */
    public static function collectAllStatuses()
    {
        return WorkStatus::asSelectArray();
    }

    /**
     * Execute the work by appointment
     *
     * @return bool
     */
    public function execute()
    {
        $this->attributes['status'] = WorkStatus::InProcess;
        $execute = $this->save();

        return $execute;
    }

    /**
     * Attach revenue to the work
     *
     * @param mixed  $revenue
     * @return bool
     */
    public function attachRevenue($revenue)
    {
        $revenueable = new Revenueable();
        $revenueable->revenueable_type = Work::class;
        $revenueable->revenueable_id = $this->attributes['id'];
        $revenueable->revenue_id = is_string($revenue)
            ? $revenue
            : $revenue->id;

        return $revenueable->save();
    }

    /**
     * Mark work as finished. To mark the work as finished
     * need to assign the work at certain appointment and
     * give optional finish note.
     *
     * THIS ACTION WILL RECORD WORK AMOUNT AS REVENUE
     *
     * @param Appointment  $appointment
     * @param string  $finishNote
     * @return bool
     */
    public function markFinished(
        Appointment $appointment,
        string $finishNote = ''
    ) {
        $this->attributes['status'] = WorkStatus::Finished;
        $this->attributes['finish_note'] = $finishNote;
        $this->attributes['finished_at'] = now();
        $this->attributes['finished_at_appointment_id'] = $appointment->id;
        $markFinsih = $this->save();
        $this->fireModelEvent('markedFinished');

        return $markFinsih;
    }

    /**
     * Mark work as unfinished to continue work at another day.
     *
     * @param string  $unfinishNote
     * @return bool
     */
    public function markUnfinished(string $unfinishNote = '')
    {
        $this->attributes['status'] = WorkStatus::Unfinished;
        $this->attributes['marked_unfinished_at'] = now();
        $this->attributes['unfinish_note'] = $unfinishNote;
        $markUnfinish = $this->save();
        $this->fireModelEvent('markedUnfinished');

        return $markUnfinish;
    }

    /**
     * Mark revenue of work as recorded.
     * When the revenue is recorded, this will prevent multiple recording of revenue.
     *
     * @return bool
     */
    public function markRevenueRecorded()
    {
        $this->attributes['revenue_recorded'] = true;

        return $this->save();
    }

    /**
     * Unmark revenue of work.
     * If work has recorded pivot table of revenueable, this method will delete it
     *
     * @return bool
     */
    public function unmarkRevenueRecorded()
    {
        if ($revenueable = $this->revenueable) {
            $revenueable->delete();
        }
        $this->attributes['revenue_recorded'] = false;

        return $this->save();
    }
}
