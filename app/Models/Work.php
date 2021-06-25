<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Work extends Model
{
    protected $table = 'works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'quotation_id',
        'work_contract_id',

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

    public function conditionPhotos()
    {
        return $this->hasMany('App\Models\WorkConditionPhoto', 'work_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation', 'id', 'quotation_id');
    }

    public function workContract()
    {
        return $this->belongsTo('App\Models\WorkContract', 'id', 'work_contract_id');
    }
}