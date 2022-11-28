<?php

namespace App\Models\Invoice;

use App\Enums\Invoice\{InvoicePaymentMethod as PaymentMethod, InvoiceStatus, InvoiceStatus as Status};
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Observers\InvoiceObserver;
use App\Services\Invoice\InvoiceService;
use App\Traits\InvoiceScopes;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class Invoice extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    use \App\Traits\Searchable;
    use InvoiceScopes;

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
    public array $searchableFields = [
        'number',
        'date',
        'due_date',
        'payment_method',
        'customer_address',
        'note',
        'amount',
        'total_amount'
    ];

    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public array $searchableRelations = [
        'customer' => [
            'fullname',
            'email',
            'phone'
        ]
    ];


    /**
     * Set columns casts
     *
     * @var array
     */
    public $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'taxes' => 'array',
    ];
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
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'customer_id',
        'customer_address',
        'number',
        'date',
        'due_date',
        'amount',
        'taxes',
        'discount_amount',
        'total_amount',
        'potential_amount',
        'status',
        'payment_method',
        'note',
        'sent_at',
        'paid_at',
    ];

    /**
     * Collect all possible statuses
     *
     * @static
     * @return array
     */
    public static function collectAllStatuses(): array
    {
        return Status::asSelectArray();
    }

    /**
     * Collect all possible payment methods
     *
     * @static
     * @return array
     */
    public static function collectAllPaymentMethods(): array
    {
        return PaymentMethod::asSelectArray();
    }

    /**
     * Collect all selectable statuses
     *
     * @static
     * @return array
     */
    public static function collectStatusOptions(): array
    {
        return [
            InvoiceStatus::Sent => 'Send Invoice',
            InvoiceStatus::Paid => 'Mark As Paid',
            InvoiceStatus::FirstReminderSent => 'Send First Reminder',
            InvoiceStatus::SecondReminderSent => 'Send Second Reminder',
            InvoiceStatus::ThirdReminderSent => 'Send Third Reminder',
            InvoiceStatus::DebtCollectorSent => 'Send To Debt Collector',
            InvoiceStatus::PaidViaDebtCollector => 'Mark As Paid Via Debt Collector',
        ];
    }

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(InvoiceObserver::class);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will return the description of status as string
     *
     * @return string
     */
    public function getStatusDescriptionAttribute(): string
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
    public function getPaymentMethodDescriptionAttribute(): string
    {
        $method = $this->attributes['payment_method'];

        return PaymentMethod::getDescription($method);
    }

    /**
     * Create callable "formatted_amount" attribute
     * This attribute will return quotation amount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return currency_format($this->attributes['amount']);
    }

    /**
     * Create callable "formatted_potential_amount" attribute
     * This attribute will return quotation potential amount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedPotentialAmountAttribute(): string
    {
        return currency_format($this->attributes['potential_amount']);
    }

    /**
     * Create callable "formatted_total_amount" attribute
     * This attribute will return quotation total amount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return currency_format($this->attributes['total_amount']);
    }

    /**
     * Create callable "formatted_discount_amount" attribute
     * This attribute will return quotation total amount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedDiscountAmountAttribute(): string
    {
        return currency_format($this->attributes['discount_amount']);
    }

    /**
     * Create callable "formatted_expiry_date" attribute
     * This callable attribute will return formatted expiry date
     * Example resulted format will be: January 01, 2021
     *
     * @return string
     */
    public function getFormattedDueDateAttribute(): string
    {
        $dueDate = $this->attributes['due_date'];

        return carbon($dueDate)->format('M d, Y');
    }

    /**
     * Create callable "formatted_expiry_date" attribute
     * This callable attribute will return formatted expiry date
     * Example resulted format will be: January 01, 2021
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        $expiryDate = $this->attributes['date'];

        return carbon($expiryDate)->format('M d, Y');
    }

    /**
     * Get company that owns current invoice
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get customer that's billed by this invoice
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Generate invoice number automatically.
     * This will return invoice number by format of YYYYMMDD0000N
     * Y => Year in number
     * M => Month in number
     * D => Day in number
     * N => Order of the invoice, start from 1 to maximum 10.000 invoices per day
     *
     * @return string
     */
    public function generateNumber(): string
    {
        // Get month and year as first 4 + 2 characters
        $now = carbon()
            ->now()
            ->copy();
        $year = $now->year;


        // Check if there is sent invoice within this year
        $latestSentInvoice = self::whereYear('created_at', $year)
            ->where('company_id', $this->attributes['company_id'])
            ->whereNotNull('number')
            ->latest()
            ->first();


        if ($latestSentInvoice) {
            $latestInvoiceNumber = $latestSentInvoice->number;
            $lastFiveCharacters = substr($latestInvoiceNumber, -5);
            $lastFiveCharacters = (int)$lastFiveCharacters;
            $lastFiveCharacters += 1;
            $invoiceNumber = 'F' . $year . $lastFiveCharacters;
        } else {
            $lastFiveDigits = str_pad(1, 5, '0', STR_PAD_LEFT);
            $invoiceNumber = 'F' . $year . $lastFiveDigits;
        }
        return $this->attributes['number'] = $invoiceNumber;
    }

    /**
     * Count total of quotation works amount
     *
     * @return void
     */
    public function countWorksAmount(): void
    {
        $invoice = $this->fresh()->load('items');
        $items = $invoice->items;
        $taxes = $this->calculateTaxes();
        $discountAmount = $this->attributes['discount_amount'];
        $taxAmount = array_sum(array_column($taxes, 'tax_amount'));
        $this->attributes['amount'] = $items->sum('total');
        $this->attributes['taxes'] = json_encode($taxes);
        $this->attributes['total_amount'] = $this->attributes['amount'] + $taxAmount - $discountAmount;
        $this->saveQuietly();
    }

    /**
     * Calculate taxes of quotation
     *
     * @return array
     */
    public function calculateTaxes(): array
    {
        $taxPercentages = $this->items()->groupBy('tax_percentage')->orderBy('tax_percentage')->pluck('tax_percentage');
        $taxes = [];
        $totalTaxAmount = 0;
        foreach ($taxPercentages as $taxPercentage) {
            $total = $this->items()->where('tax_percentage', $taxPercentage)->sum('total');
            $taxAmount = $total * ($taxPercentage / 100);
            $subTotal = $total + $taxAmount;
            $taxes[] = [
                'total' => $total,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'sub_total' => $subTotal,
            ];
            $totalTaxAmount += $taxAmount;
        }
        return $taxes;
    }

    /**
     * Get invoice items
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Create callable 'total_taxes' attribute
     * This callable attribute will return total taxes of quotation
     *
     * @return float
     */
    public function getTotalTaxesAttribute(): float
    {
        $taxes = $this->calculateTaxes();
        return array_sum(array_column($taxes, 'tax_amount'));
    }

    /**
     * Create callable 'total_taxes' attribute
     * This callable attribute will return total taxes of quotation
     *
     * @return string
     */
    public function getFormattedTotalTaxesAttribute(): string
    {
        return currencyFormat($this->total_taxes);
    }

    /**
     * Get invoice logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(InvoiceLog::class);
    }

    /**
     * Get invoice reminder
     *
     * @return HasOne
     */
    public function reminder(): HasOne
    {
        return $this->hasOne(InvoiceReminder::class);
    }

    /**
     * Get invoice setting
     *
     * @return HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(InvoiceSetting::class);
    }

    /**
     * Determine if invoice is paid
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->attributes['status'] === InvoiceStatus::Paid;
    }

    /**
     * Determine if invoice can be edit
     *
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return $this->isDrafted();
    }

    /**
     * Determine if invoice is created
     *
     * @return bool
     */
    public function isDrafted(): bool
    {
        return $this->attributes['status'] === InvoiceStatus::Drafted;
    }

    /**
     * Determine if invoice can be delete
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return $this->isDrafted();
    }

    /**
     * Determine if invoice is overdue
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->status === InvoiceStatus::PaymentOverdue or now() >= $this->due_date;
    }

    /**
     * Determine if invoice is just sent
     *
     * @return bool
     */
    public function justSent(): bool
    {
        if (!$this->wasChanged('status')) {
            return false;
        }

        if ($this->isSent()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if invoice is sent
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->attributes['status'] === InvoiceStatus::Sent;
    }

    /**
     * Get available actions for this invoice
     *
     * @return array
     */
    public function getAvailableActionsAttribute(): array
    {
        $availableActions = InvoiceService::AVAILABLE_INVOICE_ACTIONS;
        return $availableActions[$this->attributes['status']] ?? [];
    }


    /**
     * Set overdue of invoice
     *
     * @return void
     */
    public function setOverdue(): void
    {
        $this->attributes['status'] = InvoiceStatus::PaymentOverdue;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to first reminder overdue
     *
     * @return void
     */
    public function setFirstReminderOverdue(): void
    {
        $this->attributes['status'] = InvoiceStatus::FirstReminderOverdue;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to first reminder overdue
     *
     * @return void
     */
    public function setFirstReminderSent(): void
    {
        $this->attributes['status'] = InvoiceStatus::FirstReminderSent;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to third reminder overdue
     *
     * @return void
     */
    public function setThirdReminderOverdue(): void
    {
        $this->attributes['status'] = InvoiceStatus::ThirdReminderOverdue;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to third reminder overdue
     *
     * @return void
     */
    public function setThirdReminderSent(): void
    {
        $this->attributes['status'] = InvoiceStatus::ThirdReminderSent;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to second reminder overdue
     *
     * @return void
     */
    public function setSecondReminderOverdue(): void
    {
        $this->attributes['status'] = InvoiceStatus::SecondReminderOverdue;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to second reminder overdue
     *
     * @return void
     */
    public function setSecondReminderSent(): void
    {
        $this->attributes['status'] = InvoiceStatus::SecondReminderSent;
        $this->saveQuietly();
    }

    /**
     * Set status invoice to paid via debt collector
     *
     * @return void
     */
    public function setPaidViaDebtCollector(): void
    {
        $this->attributes['status'] = InvoiceStatus::PaidViaDebtCollector;
        $this->attributes['paid_at'] = now();
        $this->saveQuietly();
    }


    /**
     * Determine if invoice auto reminder activated
     *
     * @return bool
     */
    public function isAutoReminderActive(): bool
    {
        return (bool)$this->auto_reminder_activated;
    }

    /**
     * Interact with the signature.
     *
     * @return Attribute
     */
    protected function signatureUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $signatureUrl = $this->getFirstMediaUrl('signature');
                return $signatureUrl ? $signatureUrl : null;
            }
        );
    }

}
