<?php

namespace App\Models\Quotation;

use App\Models\WorkService\WorkService;
use App\Observers\QuotationItemObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

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
    public array $searchableFields = [];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'quotation_items';

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
        'quotation_id',
        'work_service_id',
        'amount',
        'unit_price',
        'total',
        'tax_percentage'
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
        self::observe(QuotationItemObserver::class);
    }

    /**
     * Get the quotation that owns the quotation item
     *
     * @return BelongsTo
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the work service that owns the quotation item
     *
     * @return BelongsTo
     */
    public function workService(): BelongsTo
    {
        return $this->belongsTo(WorkService::class);
    }

    /**
     * Create callable formatted_unit_price attribute
     *
     * @return string
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return currencyFormat($this->attributes['unit_price']);
    }

    /**
     * Create callable formatted_total attribute
     *
     * @return string
     */
    public function getFormattedTotalAttribute(): string
    {
        return currencyFormat($this->attributes['total']);
    }

}
