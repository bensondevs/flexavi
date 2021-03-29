<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class PaymentTerm extends Model
{
    protected $table = 'payment_terms';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'invoice_id',
        'term_name',
        'amount',
        'due_date',
        'reminder_count',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($paymentTerm) {
            $paymentTerm->id = Uuid::generate()->string;
    	});
    }

    public function invoice()
    {
        return $this->belongsTo(
            'App\Models\Invoice', 
            'id', 
            'invoice_id'
        );
    }
}