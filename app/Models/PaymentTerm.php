<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\PaymentTermObserver;

use App\Enums\PaymentTerm\PaymentTermStatus;

class PaymentTerm extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'payment_terms';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'term_name',
    ];

    protected $fillable = [
        'company_id',
        'invoice_id',
        'term_name',
        'status',
        'amount',
        'due_date',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(PaymentTermObserver::class);

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
        return carbon()->parse($dueDate)->format('M d, Y');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function collectAllStatuses()
    {
        return PaymentTermStatus::asSelectArray();
    }

    public function recountInvoiceTermsTotal()
    {
        $invoice = $this->invoice()->first();
        $invoice->countTermsTotal();
        return $invoice->save();
    }
}