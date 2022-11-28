<?php

namespace App\Models\WorkService;

use App\Enums\WorkService\WorkServiceStatus;
use App\Models\Company\Company;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationItem;
use App\Models\WorkContract\WorkContract;
use App\Models\WorkContract\WorkContractService;
use App\Observers\WorkServiceObserver as Observer;
use App\Traits\ModelMutators;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};


class WorkService extends Model
{

    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use HasFactory;
    use SoftDeletes;
    use \App\Traits\Searchable;
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
        'name',
        'price',
        'description',
        'status',
        'unit',
    ];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'work_services';

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
        'name',
        'price',
        'description',
        'status',
        'unit',
        'tax_percentage',
    ];

    /**
     * Collect all possible statuses of the work services
     *
     * @static
     * @return array
     */
    public static function collectAllStatuses(): array
    {
        return WorkServiceStatus::asSelectArray();
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
        self::observe(Observer::class);
    }

    /**
     * Get company of the car
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Change work service status to active
     *
     * @return bool
     */
    public function setActive(): bool
    {
        $this->attributes['status'] = WorkServiceStatus::Active;

        return $this->save();
    }

    /**
     * Change work service status to inactive
     *
     * @return bool
     */
    public function setInactive(): bool
    {
        $this->attributes['status'] = WorkServiceStatus::Inactive;

        return $this->save();
    }


    /**
     * Create callable attribute of "formatted_price"
     * This callable attribute will return the price
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return currency_format($this->attributes['price']);
    }

    /**
     * Create callable attribute of "formatted_tax_percentage"
     * This callable attribute will return the tax percentage
     *
     * @return string
     */
    public function getFormattedTaxPercentageAttribute(): string
    {
        return $this->attributes['tax_percentage'] . '%';
    }

    /**
     * Create callable attribute of "total_price"
     * This callable attribute will return the total price
     *
     * @return string
     */
    public function getTotalPriceAttribute(): string
    {
        return $this->attributes['price'] + ($this->attributes['price'] * $this->attributes['tax_percentage'] / 100);
    }

    /**
     * Create callable attribute of "formatted_total_price"
     * This callable attribute will return the total price
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return currency_format($this->attributes['price'] + ($this->attributes['price'] * $this->attributes['tax_percentage'] / 100));
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will get the description of status
     *
     * @return string
     */
    public function getStatusDescriptionAttribute(): string
    {
        return WorkServiceStatus::getDescription($this->attributes['status']);
    }

    /**
     * Create callable active() method
     * This callable method will query only car with status of active
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', WorkServiceStatus::Active);
    }

    /**
     * Create callable inactive() method
     * This callable method will query only car with status of inactive
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', WorkServiceStatus::Inactive);
    }

    /**
     * Determine if this work service is used in any work contract
     *
     * @return bool
     */
    public function isDeletable(): bool
    {
        if ($this->isActive()) {
            return false;
        }

        if ($this->quotationItems->count() > 0) {
            return false;
        }

        if ($this->invoiceItems->count() > 0) {
            return false;
        }

        if ($this->workContractItems->count() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Determine if work service is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === WorkServiceStatus::Active;
    }

    /**
     * Determine if work service is active
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return $this->status === WorkServiceStatus::Inactive;
    }

    /**
     * Quotations that use this work service
     *
     * @return HasMany
     */
    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'work_service_id', 'id');
    }

    /**
     * Invoice items that use this work service
     *
     * @return HasMany
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'work_service_id', 'id');
    }

    /**
     * Work contracts that use this work service
     *
     * @return HasMany
     */
    public function workContractItems(): HasMany
    {
        return $this->hasMany(WorkContractService::class, 'work_service_id', 'id');
    }

    /**
     * Get quotation items that use this work service
     *
     * @return BelongsToMany
     */
    public function quotations(): BelongsToMany
    {
        return $this->belongsToMany(Quotation::class, 'quotation_items', 'work_service_id', 'quotation_id');
    }

    /**
     * Get invoice items that use this work service
     *
     * @return BelongsToMany
     */
    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'invoice_items', 'work_service_id', 'invoice_id');
    }

    /**
     * Get work contract items that use this work service
     *
     * @return BelongsToMany
     */
    public function workContracts(): BelongsToMany
    {
        return $this->belongsToMany(WorkContract::class, 'work_contract_services', 'work_service_id', 'work_contract_id');
    }

}
