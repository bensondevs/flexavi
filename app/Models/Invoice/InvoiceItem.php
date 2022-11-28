<?php

namespace App\Models\Invoice;

use App\Models\WorkService\WorkService;
use App\Observers\InvoiceItemObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
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
    protected $table = 'invoice_items';

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
        'invoice_id',
        'work_service_id',
        'amount',
        'unit_price',
        'total',
        'tax_percentage',
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
        self::observe(InvoiceItemObserver::class);
    }

    /**
     * Get invoice for the invoice item
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get work service for the invoice item
     *
     * @return BelongsTo
     */
    public function workService(): BelongsTo
    {
        return $this->belongsTo(WorkService::class);
    }
}
