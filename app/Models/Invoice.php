<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\Invoice\InvoiceStatus;
use App\Enums\Invoice\InvoicePaymentMethod;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'customer_id',

        'referenceable_id',
        'referenceable_type',

        'total',
        'status',
        'payment_method',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($invoice) {
            $invoice->id = Uuid::generate()->string;
    	});
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return InvoiceStatus::getDescription($status);
    }

    public function getPaymentMethodDescriptionAttribute()
    {
        $method = $this->attributes['payment_method'];
        return InvoicePaymentMethod::getDescription($method);
    }

    public function getFormattedTotalAttribute()
    {
        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $this->attributes['total']);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function referenceable()
    {
        return $this->morphTo();
    }

    public function generateItemsFromWorks($works)
    {
        $rawItems = [];
        foreach ($works as $work) {
            $rawItems[] = new InvoiceItem([
                'invoice_id' => $this->attributes['id'],
                'work_id' => $work->id,
                'item_name' => $work->description,
                'description' => 'Referenced from work',
                'quantity' => $work->quantity,
                'quantity_unit' => $work->quantity_unit,
                'amount' => $work->unit_price,
            ]);
        }

        return $this->items()->saveMany($rawItems);
    }
}