<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\PaymentTerm\PaymentTermStatus;

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
        'status',
        'amount',
        'due_date',
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

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return PaymentTermStatus::getDescription($status);
    }

    public function getFormattedAmountAttribute()
    {
        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $this->attributes['amount']);
    }

    public function getHumanDueDateAttribute()
    {
        $dueDate = $this->attributes['due_date'];
        return carbon($dueDate)->format('M d, Y');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}