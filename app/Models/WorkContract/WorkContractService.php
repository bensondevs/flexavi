<?php

namespace App\Models\WorkContract;

use App\Models\WorkService\WorkService;
use App\Observers\WorkContractServiceObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkContractService extends Model
{
    use HasFactory;

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
     * Define the model table
     *
     * @var string
     */
    protected $table = 'work_contract_services';

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
        'id',
        'work_contract_id',
        'work_service_id',
        'amount',
        'unit_price',
        'total',
        'tax_percentage'
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(WorkContractServiceObserver::class);
    }

    /**
     * Define the work contract relationship
     *
     * @return BelongsTo
     */
    public function workContract(): BelongsTo
    {
        return $this->belongsTo(WorkContract::class);
    }

    /**
     * Define the work service relationship
     *
     * @return BelongsTo
     */
    public function workService(): BelongsTo
    {
        return $this->belongsTo(WorkService::class);
    }

    /**
     * Define new attribute `formatted_unit_price`
     *
     * @return string
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return currencyFormat($this->attributes['unit_price']);
    }

    /**
     * Define new attribute `formatted_total`
     *
     * @return string
     */
    public function getFormattedTotalAttribute(): string
    {
        return currencyFormat($this->attributes['total']);
    }

}
