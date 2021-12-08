<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Work\WorkStatus;
use App\Enums\ExecuteWork\ExecuteWorkStatus;

use App\Observers\WorkObserver;

class Work extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;
    use HasRelationships;

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
    protected $searchable = [
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

    	self::creating(function ($work) {
            $work->id = Uuid::generate()->string;
    	});

        self::saving(function ($work) {
            $work->total_price = $work->countTotalPrice();
        });
    }

    /**
     * Create callable `onlyStatus(int $status)` method
     * This callable method will query only work with certain
     * requested status based on the status given
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param int  $status
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\Appointment  $appointment
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @return double
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
     * @return double
     */
    public function getTaxAmountAttribute()
    {
        $unitTotal = $this->getUnitTotalAttribute();
        $taxPercentage = $this->attributes['tax_percentage'];
        return $unitTotal * ($taxPercentage / 100);
    }

    public function getFormattedTaxAmountAttribute()
    {
        $taxAmount = $this->getTaxAmountAttribute();

        return currency_format($taxAmount);
    }

    public function getFormattedTotalPriceAttribute()
    {
        $totalPrice = $this->attributes['total_price'];

        return currency_format($totalPrice);
    }

    public function getFormattedTotalPaidAttribute()
    {
        $totalPaid = $this->attributes['total_paid'];

        return currency_format($totalPaid);
    }

    /**
     * Count the total price of the work
     * The total is acquired by multiplication of quantity and unit_price
     * 
     * @return double
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
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get quotations of the work
     */
    public function quotations()
    {
        return $this->morphedByMany(Quotation::class, 'workable');
    }

    /**
     * Create callable attribute of "quotation"
     * This callable attribute will return one quotation
     * 
     * @return \App\Models\Quotation
     */
    public function getQuotationAttribute()
    {
        return $this->quotations->first();
    }

    /**
     * Get appointments of work
     */
    public function appointments()
    {
        return $this->morphedByMany(Appointment::class, 'workable');
    }

    /**
     * Create callable attribute of "appointment"
     * This callable attribute will return one appointment
     * 
     * @return \App\Models\Appointment
     */
    public function getAppointmentAttribute()
    {
        return $this->appointments->first();
    }

    /**
     * Get appointment where work is finished
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
     */
    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    /**
     * Get current execution of work 
     */
    public function currentExecuteWork()
    {
        return $this->hasOne(ExecuteWork::class)
            ->where('status', ExecuteWorkStatus::InProcess);
    }

    /**
     * Get revenueable type of this work
     */
    public function revenueable()
    {
        return $this->morphOne(Revenueable::class, 'revenueable')
            ->oldestOfMany();
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
     * @param \App\Models\Appointment  $appointment
     * @return bool
     */
    public function execute(Appointment $appointment)
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
        $revenueable->revenue_id = is_string($revenue) ? 
            $revenue : $revenue->id;
        return $revenueable->save();
    }

    /**
     * Mark work as finished. To mark the work as finished
     * need to assign the work at certain appointment and 
     * give optional finish note. 
     * 
     * THIS ACTION WILL RECORD WORK AMOUNT AS REVENUE
     * 
     * @param App\Models\Appointment  $appointment
     * @param string  $finishNote
     * @return bool
     */
    public function markFinished(Appointment $appointment, string $finishNote = '')
    {
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