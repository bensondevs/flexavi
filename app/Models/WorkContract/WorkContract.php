<?php

namespace App\Models\WorkContract;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\WorkContract\WorkContractStatus;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Observers\WorkContractObserver;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class WorkContract extends Model implements HasMedia
{
    use HasFactory;

    use SoftDeletes;
    use InteractsWithMedia;
    use Searchable;

    /**
     * Define the model timestamp option
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Define the model id incremental type
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Set columns casts
     *
     * @var array
     */
    public $casts = [
        'taxes' => 'array',
    ];

    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public array $searchableFields = ['number', 'total_amount', 'potential_amount', 'status'];
    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public array $searchableRelations = [
        'customer' => ['fullname', 'email', 'phone'],
    ];


    /**
     * Define the model table
     *
     * @var string
     */
    protected $table = 'work_contracts';
    /**
     * Define the model id column
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Define the model fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',
        'appointment_id',
        'description',
        'number',
        'amount',
        'discount_amount',
        'potential_amount',
        'total_amount',
        'status',
        'footer',
        'nullified_at',
        'sent_at',
        'signed_at',
        'taxes'
    ];

    /**
     * Collect all possible quotation statuses enums as array
     *
     * @static
     * @return array
     */
    public static function collectAllStatuses(): array
    {
        return WorkContractStatus::asSelectArray();
    }

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(WorkContractObserver::class);
    }

    /**
     * Define the company relation
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Define the company relation
     *
     * @return BelongsTo
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get relation of work contract contents
     *
     * @return HasMany
     */
    public function forewordContents(): HasMany
    {
        return $this->contents()->where('position_type', WorkContractContentPositionType::Foreword);
    }

    /**
     * Get work contract content
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(WorkContractContent::class);
    }

    /**
     * Get relation of work contract signatures
     *
     * @return HasMany
     */
    public function signatures(): HasMany
    {
        return $this->hasMany(WorkContractSignature::class);
    }

    /**
     * Get relation of work contract customer
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get relation of work contract contents
     *
     * @return HasMany
     */
    public function contractContents(): HasMany
    {
        return $this->contents()->where('position_type', WorkContractContentPositionType::Contract);
    }

    /**
     * Get Logo url attribute
     *
     * @return string|null
     */
    public function getLogoUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('logo');
        return $media?->getFullUrl();
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
     * Create callable "formatted_footer" attribute
     * This attribute will return footer formatted
     *
     * @return string
     */
    public function getFormattedFooterAttribute(): string
    {
        $service = app(\App\Services\WorkContract\WorkContractService::class);
        return $service->formatContentWithTemplatingService($this, $this->attributes['footer']);
    }

    /**
     * Create callable "formatted_potential_amount" attribute
     * This attribute will return quotation amount
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
        return floatval($this->attributes["total_amount"]) - floatval($this->attributes["potential_amount"]);
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
        $overallDiscount =
            floatval($this->attributes["total_amount"]) - floatval($this->attributes["potential_amount"]);
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
     * Create callable "status_description" attribute
     * This callable attribute will return description of status enum
     *
     * @return string
     */
    public function getStatusDescriptionAttribute(): string
    {
        $status = $this->attributes['status'];
        return WorkContractStatus::getDescription($status);
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
     * Count total of quotation works amount
     *
     * @return void
     */
    public function countWorksAmount(): void
    {
        $workContract = $this->fresh()->load('services');
        $items = $workContract->services;
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
        $taxPercentages = $this->services()->groupBy('tax_percentage')->orderBy('tax_percentage')->pluck('tax_percentage');
        $taxes = [];
        $totalTaxAmount = 0;
        foreach ($taxPercentages as $taxPercentage) {
            $total = $this->services()->where('tax_percentage', $taxPercentage)->sum('total');
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
     * Get relation of work contract services
     *
     * @return HasMany
     */
    public function services(): HasMany
    {
        return $this->hasMany(WorkContractService::class);
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
     * Create settable "formatted_total_amount_excluding_tax" attribute
     * This callable attribute will return total amount excluding VAT in formatted currency
     * @return string
     */
    public function getFormattedTotalAmountExcludingTaxAttribute(): string
    {
        return currency_format($this->total_amount_excluding_tax);
    }

    /**
     * Create callable `total_amount_excluding_tax` attribute
     * This callable attribute will return total amount excluding tax
     *
     * @return float
     */
    public function getTotalAmountExcludingTaxAttribute(): float
    {
        $amount = $this->attributes['amount'] ?? 0;
        $discountAmount = $this->attributes['discount_amount'];
        return $amount + $discountAmount;
    }

    /**
     * Determine if work contract can be deleted
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return $this->canBeNullified() || $this->getFirstMediaUrl('signed_document') === null;
    }

    /**
     * Determine if work contract can be nullified
     *
     * @return bool
     */
    public function canBeNullified(): bool
    {
        return $this->getFirstMediaUrl('signed_document') !== null;
    }

    /**
     * Determine if work contract is nullified
     *
     * @return bool
     */
    public function isNullified(): bool
    {
        return $this->status === WorkContractStatus::Nullified;
    }

    /**
     * Determine if work contract can be nullified
     *
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return $this->status == WorkContractStatus::Drafted;
    }

    /**
     * Determine if work contract can be sign
     *
     * @return bool
     */
    public function canBeSigned(): bool
    {
        return $this->status == WorkContractStatus::Sent;
    }

    /**
     * Determine if work contract can be nullified
     *
     * @return bool
     */
    public function isDrafted(): bool
    {
        return $this->status == WorkContractStatus::Drafted;
    }

    /**
     * Determine if work contract can be nullified
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->status === WorkContractStatus::Sent;
    }

    /**
     * Determine if work contract is signed
     *
     * @return bool
     */
    public function isSigned(): bool
    {
        return $this->status === WorkContractStatus::Signed;
    }

    /**
     * Get signed document
     *
     * @return array
     */
    public function getSignedDocumentAttribute(): array
    {
        $media = $this->getFirstMedia('signed_document');
        return [
            'url' => $media?->getFullUrl(),
            'file_name' => $media?->file_name,
        ];
    }

    /**
     * Get signature url
     *
     * @return string|null
     */
    public function getSignatureUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('signature');
        return $media?->getFullUrl();
    }

    /**
     * Get signature name
     *
     * @return string|null
     */
    public function getSignatureNameAttribute(): ?string
    {
        $media = $this->getFirstMedia('signature');
        return $media?->name;
    }

    /**
     * Check if work contract just sent
     *
     * @return bool
     */
    public function justSent(): bool
    {
        if (!$this->wasChanged('status')) {
            return false;
        }

        if ($this->status == WorkContractStatus::Sent) {
            return true;
        }
        return false;
    }

}
