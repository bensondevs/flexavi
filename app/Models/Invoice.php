<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\InvoiceObserver;

use App\Enums\Invoice\{
    InvoiceStatus as Status,
    InvoicePaymentMethod as PaymentMethod
};
use App\Enums\PaymentTerm\PaymentTermStatus;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'invoice_number',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',

        'invoice_number',

        'invoiceable_id',
        'invoiceable_type',

        'total',
        'total_in_terms',
        'total_paid',
    
        'status',
        'payment_method',

        'sent_at',
        'paid_at',
        'payment_overdue_at',
        'first_remider_sent_at',
        'first_reminder_overdue_at',
        'second_reminder_overdue_at',
        'third_reminder_overdue_at',
        'overdue_debt_collector_at',
        'debt_collector_sent_at',
        'paid_via_debt_collector_at',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(InvoiceObserver::class);

    	self::creating(function ($invoice) {
            $invoice->id = Uuid::generate()->string;

            if (! $invoice->invoice_number) {
                $invoice->invoice_number = $invoice->generateNumber();
            }
    	});
    }

    /**
     * Create callable method of overdue()
     * This callable method will query only overdue invoices
     * 
     * @param Illuminate\Database\Eloquent\Builder  $quert
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue(Builder $query)
    {
        return $query->where('status', '>=', Status::PaymentOverdue)
            ->where('status', '<=', Status::DebtCollectorSent);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will return the description of status as string
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Create callable attribute of "payment_method_description"
     * This callable attribute will return the description of the payment method
     * 
     * @return string
     */
    public function getPaymentMethodDescriptionAttribute()
    {
        $method = $this->attributes['payment_method'];
        return PaymentMethod::getDescription($method);
    }

    /**
     * Create callable attribute of "formatted_total"
     * This callable attribute will return the invoice total with currency format
     * 
     * @return string
     */
    public function getFormattedTotalAttribute()
    {
        $total = $this->attributes['total'];
        return currency_format($total);
    }

    /**
     * Create callable attribute of "formatted_total_in_terms"
     * This callable attribute will return the amount of total-
     * that billed in the payment terms under this invoice
     * 
     * @return string
     */
    public function getFormattedTotalInTermsAttribute()
    {
        $totalInTerms = $this->attributes['total_in_terms'];
        return currency_format($totalInTerms);
    }

    /**
     * Create callable attribute of "total_out_terms"
     * This callable attribute will return the amounts which are-
     * not billed in the payment terms under this invoice
     * 
     * @return double 
     */
    public function getTotalOutTermsAttribute()
    {
        $total = $this->attributes['total'];
        $totalInTerms = $this->attributes['total_in_terms'];

        return $total - $totalInTerms;
    }

    /**
     * Create callable attribute of "formatted_total_out_terms"
     * This callable attribute will return currency-formatted version of 
     * "total_out_terms" callable attribute.
     * 
     * @return string
     */
    public function getFormattedTotalOutTermsAttribute()
    {
        $totalOutTerms = $this->getTotalOutTermsAttribute();
        return currency_format($totalOutTerms);
    }

    /**
     * Create callable attribute of "formatted_total_paid"
     * This callable attribute will return currency-formatted version of
     * "total_paid" callable attribute
     * 
     * @return string
     */
    public function getFormattedTotalPaidAttribute()
    {
        $totalPaid = $this->attributes['total_paid'];
        return currency_format($totalPaid);
    }

    /**
     * Create callable attribute of "total_unpaid"
     * This callable attribute will return amount of unpaid total
     * 
     * @return double
     */
    public function getTotalUnpaidAttribute()
    {
        $total = $this->attributes['total'];
        $paid = $this->attributes['total_paid']; 

        return $total - $paid;
    }

    /**
     * Create callable attribute of "formatted_total_unpaid"
     * This callable attribute will return currency-formatted version of
     * "total_unpaid" callable attribute
     * 
     * @return string
     */
    public function getFormattedTotalUnpaidAttribute()
    {
        $totalUnpaid = $this->getTotalUnpaidAttribute();
        return currency_format($totalUnpaid);
    }

    /**
     * Get which columns is the target of payment
     * This selected column will be the column that become
     * target of substraction when payment is done
     * 
     * @return string
     */
    public function getPayableColumnAttibute()
    {
        return 'total_paid';
    }

    /**
     * Get amount that should be paid
     * 
     * @return double
     */
    public function getShouldBePaidAmountAttribute()
    {
        return $this->total_unpaid;
    }

    /**
     * Set added paid amount after the payment
     * 
     * @return void
     */
    public function setAddedPaidAmountAttribute(double $amount);

    /**
     * Get invoice items
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get payment terms of the invoice
     */
    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class);
    }

    /**
     * Get company that owns current invoice
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get model that attached by this invoice
     */
    public function invoiceable()
    {
        return $this->morphTo();
    }

    /**
     * Get customer that's billed by this invoice
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Collect all possible payment methods
     * 
     * @static
     * @return array
     */
    public static function collectAllPaymentMethods()
    {
        return PaymentMethod::asSelectArray();
    }

    /**
     * Collect all possible statuses
     * 
     * @static
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Collect all possible selected status options
     * 
     * @static
     * @return array
     */
    public static function collectStatusOptions()
    {
        $statuses = self::collectAllStatuses();
        for ($removeableIndex = 1; $removeableIndex < 5; $removeableIndex++) {
            unset($statuses[$removeableIndex]);
        }

        return $statuses;
    }

    /**
     * Generate invoice number automatically.
     * This will return invoice number by format of YYYYMMDD0000N
     * Y => Year in number
     * M => Month in number
     * D => Day in number
     * N => Order of the invoice, start from 1 to maximumly 10.000 invoices per day
     * 
     * @return string
     */
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
            ->where('status', '>=', Status::Sent)
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

    /**
     * Generate invoice items from a collection of works thats
     * billed by this invoice
     * 
     * @param mixed $works
     * @return bool
     */
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

    /**
     * Count total of invoice items by multiplying 
     * invoice item quantity and invoice item amount
     * 
     * @return double
     */
    public function countTotal()
    {
        return $this->attributes['total'] = $this->items()->sum(
            DB::raw('invoice_items.quantity * invoice_items.amount')
        );
    }

    /**
     * Count terms of invoice
     * This function will set the value of "total_in_terms" and "total_paid"
     * with synched data from database
     * 
     * @return void
     */
    public function countTermsTotal()
    {
        $terms = $this->paymentTerms()->get();
        $this->attributes['total_in_terms'] = $terms->sum('amount');

        $paidTerms = $terms->where('status', PaymentTermStatus::Paid);
        $this->attributes['total_paid'] = $paidTerms->sum('amount');
    }

    public function syncStatus()
    {
        $isOverdue = false;

        $status = $this->attributes['status'];
        if ($status == Status::Sent) {
            //
        }

        if ($isOverdue) {
            $this->attributes['status']++;
            $this->save();
        }

        return $this->attributes['status'];
    }
}