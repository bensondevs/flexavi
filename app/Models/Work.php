<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

use App\Enums\Work\WorkStatus;
use App\Enums\ExecuteWork\ExecuteWorkStatus;

use App\Observers\WorkObserver;

class Work extends Model
{
    use Searchable;
    use SoftDeletes;
    use HasRelationships;

    protected $table = 'works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'quantity_unit',
        'description',
        'note',
        'unfinish_note',
        'finish_note',
    ];

    protected $observeables = [
        'executed',
        'processed', 
        'markedFinsihed', 
        'markedUnfinished'
    ];

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

    protected $casts = [
        'include_tax' => 'boolean',
    ];

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

    public function countTotalPrice()
    {
        $quantity = $this->attributes['quantity'];
        $unitPrice = $this->attributes['unit_price'];
        $total = $quantity * $unitPrice;

        return $this->attributes['total_price'] = $total;
    }

    public function scopeOnlyStatus($query, int $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFinishedAt($query, Appointment $appointment)
    {
        return $query->where('finished_at_appointment_id', $appointment->id);
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return WorkStatus::getDescription($status);
    }

    public function getUnitTotalAttribute()
    {
        $quantity = $this->attributes['quantity'];
        $unitPrice = $this->attributes['unit_price'];

        return $quantity * $unitPrice;
    }

    public function getFormattedUnitTotalAttribute()
    {
        $unitTotal = $this->getUnitTotalAttribute();

        return currency_format($unitTotal);
    }

    public function getFormattedTaxPercentageAttribute()
    {
        $percentage = $this->attributes['tax_percentage'];

        return $percentage . '%';
    }

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

    public function quotations()
    {
        return $this->morphedByMany(Quotation::class, 'workable');
    }

    public function getQuotationAttribute()
    {
        return $this->quotations->first();
    }

    public function appointments()
    {
        return $this->morphedByMany(Appointment::class, 'workable');
    }

    public function getAppointmentAttribute()
    {
        return $this->appointments->first();
    }

    public function finishedAtAppointment()
    {
        return $this->belongsTo(
            Appointment::class, 
            'finished_at_appointment_id', 
            'id'
        );
    }

    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    public function currentExecuteWork()
    {
        return $this->hasOne(ExecuteWork::class)
            ->where('status', ExecuteWorkStatus::InProcess);
    }

    public function revenueable()
    {
        return $this->morphOne(Revenueable::class, 'revenueable')
            ->oldestOfMany();
    }

    public static function collectAllStatuses()
    {
        return WorkStatus::asSelectArray();
    }

    public function execute(Appointment $appointment)
    {
        $this->attributes['status'] = WorkStatus::InProcess;
        $execute = $this->save();

        return $execute;
    }

    public function attachRevenue($revenue)
    {
        $revenueable = new Revenueable();
        $revenueable->revenueable_type = Work::class;
        $revenueable->revenueable_id = $this->attributes['id'];
        $revenueable->revenue_id = is_string($revenue) ? 
            $revenue : $revenue->id;
        return $revenueable->save();
    }

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

    public function markUnfinished(string $unfinishNote = '')
    {
        $this->attributes['status'] = WorkStatus::Unfinished;
        $this->attributes['marked_unfinished_at'] = now();
        $this->attributes['unfinish_note'] = $unfinishNote;
        $markUnfinish = $this->save();

        $this->fireModelEvent('markedUnfinished');

        return $markUnfinish;
    }

    public function markRevenueRecorded()
    {
        if (! $this->attributes['revenue_recorded']) {
            $this->attributes['revenue_recorded'] = true;
        }
        return $this->save();
    }

    public function unmarkRevenueRecorded()
    {
        $this->attributes['revenue_recorded'] = false;
        return $this->save();
    }
}