<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const STATUSES = [
        [
            'id' => 1,
            'label' => 'Created / Draft',
        ],
        [
            'id' => 2,
            'label' => 'Send / Definitive',
        ],
        [
            'id' => 3,
            'label' => 'Paid',
        ],
        [
            'id' => 4,
            'label' => 'Payment Overdue',
        ],
        [
            'id' => 5,
            'label' => 'Overdue, send first reminder?',
        ],
        [
            'id' => 6,
            'label' => 'First Reminder Sent',
        ],
        [
            'id' => 7,
            'label' => 'First reminder sent, send the second reminder?',
        ],
        [
            'id' => 8,
            'label' => 'Second reminder sent',
        ],
        [
            'id' => 9,
            'label' => 'Second reminder sent, send the third reminder?',
        ],
        [
            'id' => 10,
            'label' => 'Third reminder sent',
        ],
        [
            'id' => 11,
            'label' => 'Overdue, debt collector?',
        ],
        [
            'id' => 12,
            'label' => 'Sent to debt collector',
        ],
        [
            'id' => 13,
            'label' => 'Paid via Debt collector',
        ]
    ];

    protected $fillable = [
        'company_id',
        'work_contract_id',
        'total',
        'status_code',
        'payment_method',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($invoice) {
            $invoice->id = Uuid::generate()->string;
    	});
    }

    public function getStatusAttribute()
    {
        $statusId = $this->attributes['status_code'];
        $statuses = collect(self::STATUSES);
        $status = $statuses->where('id', $statusId)->first();

        return $status;
    }

    public function getStatusLabelAttribute()
    {
        $status = $this->getStatusArrayAttribute();
        return $status['label'];
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

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company', 
            'company_id', 
            'id'
        );
    }
}