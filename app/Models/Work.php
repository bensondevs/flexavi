<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Work\WorkStatus;

use App\Observers\WorkObserver;

class Work extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $observeables = [
        'executed',
        'processed', 
        'markFinsihed', 
        'markUnfinished'
    ];

    protected $fillable = [
        'appointment_id',
        'quotation_id',
        'work_contract_id',

        'status',

        'quantity',
        'quantity_unit',
        'description',
        'unit_price',
        'include_tax',
        'tax_percentage',
        'total_price',
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

        if ($this->attributes['include_tax']) {
            $taxPercentage = $this->attributes['tax_percentage'];
            $total += ($total * ($taxPercentage / 100));
        }

        return $this->attributes['total_price'] = $total;
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

    public function conditionPhotos()
    {
        return $this->hasMany(WorkConditionPhoto::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function contract()
    {
        return $this->belongsTo(WorkContract::class);
    }

    public function executeWorks()
    {
        return $this->hasMany(ExecuteWork::class);
    }

    public static function collectAllStatuses()
    {
        return WorkStatus::asSelectArray();
    }

    public function execute()
    {
        $this->attributes['status'] = WorkStatus::InProcess;
        $execute = $this->save();

        $this->fireModelEvent('executed');

        return $execute;
    }

    public function process()
    {
        $this->attributes['status'] = WorkStatus::Processed;
        $this->attributes['executed_at'] = now();
        $process = $this->save();

        $this->fireModelEvent('processed');

        return $process;
    }

    public function markFinished(string $finishNote = '')
    {
        $this->attributes['status'] = WorkStatus::Finished;
        $this->attributes['finished_at'] = now();
        $markFinsih = $this->save();

        $this->fireModelEvent('markFinsihed');

        return $markFinsih;
    }

    public function markUnfinished(string $unfinishNote = '')
    {
        $this->attributes['status'] = WorkStatus::Unfinished;
        $this->attributes['marked_unfinished_at'] = now();
        $this->attributes['unfinish_note'] = $unfinishNote;
        $markUnfinish = $this->save();

        $this->fireModelEvent('markUnfinished');

        return $markUnfinish;
    }
}