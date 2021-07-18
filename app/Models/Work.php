<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\Work\WorkStatus;

class Work extends Model
{
    protected $table = 'works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

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

        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $unitTotal);
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

        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $taxAmount);
    }

    public function getFormattedTotalPriceAttribute()
    {
        $totalPrice = $this->attributes['total_price'];

        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $totalPrice);
    }

    public function conditionPhotos()
    {
        return $this->hasMany('App\Models\WorkConditionPhoto', 'work_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation', 'quotation_id', 'id');
    }

    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment', 'appointment_id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo('App\Models\WorkContract', 'work_contract_id', 'id');
    }

    public static function collectAllStatuses()
    {
        return WorkStatus::asSelectArray();
    } 
}