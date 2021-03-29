<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'work_contract_id',
        'total',
        'payment_status',
        'payment_method',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($invoice) {
            $invoice->id = Uuid::generate()->string;
    	});
    }

    public function workContract()
    {
        return $this->hasOne(
            'App\Models\WorkContract', 
            'id', 
            'work_contract_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            'App\Models\InvoiceItem', 
            'invoice_id', 
            'id'
        );
    }

    public function paymentTerms()
    {
        return $this->hasMany(
            'App\Models\PaymentTerm', 
            'invoice_id', 
            'id'
        );
    }
}