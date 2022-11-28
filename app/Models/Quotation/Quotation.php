<?php

namespace App\Models\Quotation;

use App\Enums\Quotation\{QuotationStatus as Status};
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Observers\QuotationObserver;
use App\Traits\ModelMutators;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphOne};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quotation extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use Searchable;
    use ModelMutators;

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
        'customer_address',
        'note',
        'date',
        'expiry_date',
        'total_amount',
        'potential_amount',
    ];

    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public array $searchableRelations = [
        'customer' => ['fullname', 'email', 'phone'],
        'media' => ['name', 'file_name'],
    ];


    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'quotations';

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
        'company_id',
        'customer_id',
        'appointment_id',
        'date',
        'expiry_date',
        'number',
        'customer_address',
        'note',
        'amount',
        'potential_amount',
        'vat_percentage',
        'discount_amount',
        'total_amount',
        'status',
        'sent_at',
        'signed_at',
        'nullified_at'
    ];

    /**
     * Set which attribute that should be cast
     *
     * @var array
     */
    protected $casts = [
        'nullified_at' => 'datetime',
        'send_at' => 'datetime',
        'signed_at' => 'datetime',
        'date' => 'date',
        'expiry_date' => 'date',
        'taxes' => 'array',
    ];

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
        self::observe(QuotationObserver::class);
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
        return currency_format($this->attributes['amount'] ?? 0);
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
     * Create callable "overall_discount" attribute
     * This attribute will return quotation overall discount
     * in currency formatted form.
     *
     * @return string
     */
    public function getOverallDiscountAttribute(): string
    {
        return floatval($this->attributes["total_amount"] ?? 0) - floatval($this->attributes["potential_amount"]);
    }

    /**
     * Create callable "formatted_overall_discount" attribute
     * This attribute will return quotation overall discount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedOverallDiscountAttribute(): string
    {
        $overallDiscount = floatval($this->attributes["total_amount"] ?? 0) - floatval($this->attributes["potential_amount"]);
        return currency_format($overallDiscount);
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
        return currency_format($this->attributes['total_amount'] ?? 0);
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
     * Create callable "formatted_vat_percentage" attribute
     * This callable attribute will return percentage of Quotation VAT
     *
     * @return string
     */
    public function getFormattedVatPercentageAttribute(): string
    {
        return '';
    }

    /**
     * Create callable "formatted_date" attribute
     * This callable attribute will return formatted expiry date
     * Example resulted format will be: January 01, 2021
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        $date = carbon($this->date);

        return $date->format('d-M-Y');
    }

    /**
     * Create callable "formatted_expiry_date" attribute
     * This callable attribute will return formatted expiry date
     * Example resulted format will be: January 01, 2021
     *
     * @return string
     */
    public function getFormattedExpiryDateAttribute(): string
    {
        $expiryDate = carbon($this->expiry_date);

        return $expiryDate->format('d-M-Y');
    }

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return description of status enum
     *
     * @return string
     */
    public function getStatusDescriptionAttribute(): string
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }


    /**
     * Create callable "signature_url" attribute
     * This callable attribute will return signature url
     *
     * @return string|null
     */
    public function getSignatureUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('signature') ?? null;
    }

    /**
     * Create settable "discount_percentage" attribute
     * This settable attribute will allow set discount percentage
     * like real percentage (eg: 30.5%)
     *
     * @param string $percentage
     * @return void
     */
    public function setDiscountPercentageAttribute(string $percentage): void
    {
        $percentage = str_replace('%', '', $percentage);
        $percentage = (float)$percentage;
        $amount = $this->attributes['amount'];
        $this->attributes['discount_amount'] = $amount * ($percentage / 100);
    }

    /**
     * Determine if the quotation is deletable.
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return $this->canBeNullified() || $this->getFirstMediaUrl('signature') === null;
    }

    /**
     * Determine if the quotation can ben nullified.
     *
     * @return bool
     */
    public function canBeNullified(): bool
    {
        return $this->getFirstMediaUrl('signed_document') !== null;
    }

    /**
     * Determine if the quotation is deletable.
     *
     * @return bool
     */
    public function canBeSigned(): bool
    {
        return $this->status === Status::Sent;
    }

    /**
     * Determine if the quotation is deletable.
     *
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return $this->status === Status::Drafted;
    }

    /**
     * Update quotation to sent status
     *
     * @return bool
     */
    public function setSent(): bool
    {
        $this->attributes['status'] = Status::Sent;
        $this->attributes['sent_at'] = now();
        return $this->save();
    }

    /**
     * Update quotation to sent status
     *
     * @return bool
     */
    public function setNullified(): bool
    {
        $this->attributes['status'] = Status::Nullified;
        $this->attributes['nullified_at'] = now();
        return $this->save();
    }

    /**
     * Update quotation to sent status
     *
     * @return bool
     */
    public function setSigned(): bool
    {
        $this->attributes['status'] = Status::Signed;
        $this->attributes['signed_at'] = now();
        return $this->save();
    }

    /**
     * Get appointment of quotation
     *
     * @return BelongsTo
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get target customer of quotation
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get company of the quotation
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get invoice of quotation
     *
     * @return MorphOne
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    /**
     * Count total of quotation works amount
     *
     * @return void
     */
    public function countWorksAmount(): void
    {
        $quotation = $this->fresh()->load('items');
        $items = $quotation->items;
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
     * Create callable "items" attribute and get
     * quoted works model data
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
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
     * Create callable "logs" attribute and get
     * quotation logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(QuotationLog::class);
    }

    /**
     * Get signed doc attribute
     *
     * @return array|null
     */
    public function getSignedDocumentAttribute(): ?array
    {
        $media = $this->getFirstMedia('signed_document');
        return [
            'url' => $media?->getFullUrl(),
            'file_name' => $media?->file_name,
        ];
    }

    /**
     * Check whether quotation has signed document.
     *
     * @return bool
     */
    public function hasSignedDocument(): bool
    {
        $media = $this->getFirstMedia('signed_document');

        return boolval($media);
    }

    /**
     * Check quotation is sent or not
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->attributes['status'] === Status::Sent;
    }

    /**
     * Check quotation is sent or not
     *
     * @return bool
     */
    public function isDrafted(): bool
    {
        return $this->attributes['status'] === Status::Drafted;
    }

    /**
     * Check quotation is signed or not
     *
     * @return bool
     */
    public function isSigned(): bool
    {
        return $this->attributes['status'] === Status::Signed;
    }

    /**
     * Check quotation is nullified or not
     *
     * @return bool
     */
    public function isNullified(): bool
    {
        return $this->attributes['status'] === Status::Nullified;
    }
}
