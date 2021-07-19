<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\InvoiceObserver;

use App\Enums\Invoice\InvoiceStatus;
use App\Enums\Invoice\InvoicePaymentMethod;

class Invoice extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'invoices';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'invoice_number',
    ];

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
        self::observe(InvoiceObserver::class);

    	self::creating(function ($invoice) {
            $invoice->id = Uuid::generate()->string;
    	});
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '>=', InvoiceStatus::PaymentOverdue)
            ->where('status', '<=', InvoiceStatus::SentDebtCollector);
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

    public function collectAllPaymentMethods()
    {
        return InvoicePaymentMethod::asSelectArray();
    }

    public static function collectAllStatuses()
    {
        return InvoiceStatus::asSelectArray();
    }

    public static function collectStatusOptions()
    {
        $statuses = self::collectAllStatuses();
        for ($removeableIndex = 1; $removeableIndex < 5; $removeableIndex++) {
            unset($statuses[$removeableIndex]);
        }

        return $statuses;
    }

    public function generateNumber()
    {
        // Get month and year as first 4 + 2 characters
        $now = carbon()->now()->copy();
        $year = $now->year;
        $month = $now->format('m');

        // Check if there is sent invoice within this year
        $startOfYear = $now->startOfYear();
        $latestSentInvoice = self::where('created_at', '>=', $startOfYear)
            ->where('company_id', $this->attributes['company_id'])
            ->where('status', '>=', InvoiceStatus::Sent)
            ->whereNotNull('invoice_number')
            ->latest()
            ->first();
        if ($latestSentInvoice) {
            $latestInvoiceNumber = (int) $latestSentInvoice->invoice_number;

            // Double check and make sure that there is no invoice with this number
            while (
                self::where('invoice_number', (string) $latestInvoiceNumber)
                ->where('company_id', $this->attributes['company_id'])
                ->count() > 0
            ) {
                $latestInvoiceNumber++;
            }

            $invoiceNumber = (string) ($latestInvoiceNumber);
        } else {
            $lastFiveDigits = str_pad(1, 5, '0', STR_PAD_LEFT);
            $invoiceNumber = $year . $month . $lastFiveDigits;
        }

        return $this->attributes['invoice_number'] = $invoiceNumber;
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

    public function countTotal()
    {
        $items = $this->items()->get();
        $total = 0;
        foreach ($items as $item) {
            $total += $item->total;
        }

        $this->attributes['total'] = $total;
    }

    public function countTermsTotal()
    {
        $terms = $this->paymentTerms()->get();
        $this->attributes['total_in_terms'] = $terms->sum('amount');
    }
}