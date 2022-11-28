<?php

namespace App\Models\ExecuteWork;

use App\Enums\ExecuteWork\WarrantyTimeType;
use App\Models\WorkService\WorkService;
use App\Observers\WorkWarrantyObserver as Observer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class WorkWarranty extends Model
{
    use HasFactory;


    /**
     * Set autoload relation
     *
     * @var string
     */
    protected $with = ['workService'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'work_warranties';

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
    public $searchableFields = ['note', 'finish_note'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'execute_work_photo_id',
        'quantity',
        'quantity_unit',
        'work_service_id',
        'unit_price',
        'include_tax',
        'tax_percentage',
        'total_price',
        'total_paid',
        'warranty_time_value',
        'warranty_time_type',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Create callable attribute of "warranty_time_type_description"
     * This callable attribute will return the description of warranty time type as string
     *
     * @return string
     */
    public function getWarrantyTimeTypeDescription()
    {
        $status = $this->attributes['warranty_time_type'];

        return WarrantyTimeType::getDescription($status);
    }

    /**
     * Create callable attribute of "formatted_tax_percentage"
     * This callable attribute will return the description of warranty time type as string
     *
     * @return string
     */
    public function getFormattedTaxPercentage()
    {
        return $this->attributes['tax_percentage'] . ' %';
    }

    /**
     * Create callable attribute of "formatted_total_price"
     * This callable attribute will return the formatted total price of work
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute()
    {
        $totalPrice = $this->attributes['total_price'];

        return currency_format($totalPrice);
    }

    /**
     * Create callable attribute of "formatted_total_paid"
     * This callable attribute will return the formatted total paid of work
     *
     * @return string
     */
    public function getFormattedTotalPaidAttribute()
    {
        $totalPaid = $this->attributes['total_paid'];

        return currency_format($totalPaid);
    }

    /**
     * Create callable "unit_total" attribute
     * This callable attribute will return the unit total of the work
     *
     * @return float
     */
    public function getUnitTotalAttribute()
    {
        $quantity = $this->attributes['quantity'];
        $unitPrice = $this->attributes['unit_price'];

        return $quantity * $unitPrice;
    }

    /**
     * Create callable "formatted_unit_total" attribute
     * This callable attribute will return the currency formatted
     * unit total of the work
     *
     * @return string
     */
    public function getFormattedUnitTotalAttribute()
    {
        $unitTotal = $this->getUnitTotalAttribute();

        return currency_format($unitTotal);
    }

    /**
     * Get work service of the work
     *
     * @return BelongsTo
     */
    public function workService()
    {
        return $this->belongsTo(WorkService::class);
    }


    /**
     * Get work service of the work
     *
     * @return BelongsTo
     */
    public function executeWorkPhoto()
    {
        return $this->belongsTo(ExecuteWorkPhoto::class);
    }
}
