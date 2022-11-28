<?php

namespace App\Models\WorkContract;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Observers\WorkContractContentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkContractContent extends Model
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
    protected $table = 'work_contract_contents';

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
        'description',
        'order_index',
        'position_type',
        'text_type',
        'text'
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(WorkContractContentObserver::class);
        self::addGlobalScope('orderIndex', function ($query) {
            $query->orderBy('order_index', 'asc');
        });
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
     * create callable the work contract content position type attribute
     *
     * @return string
     */
    public function getPositionTypeDescription(): string
    {
        return WorkContractContentPositionType::getDescription($this->attributes['position_type']);
    }

    /**
     * Create callable "formatted_footer" attribute
     * This attribute will return footer formatted
     *
     * @return string
     */
    public function getFormattedTextAttribute(): string
    {
        $service = app(\App\Services\WorkContract\WorkContractService::class);
        return $service->formatContentWithTemplatingService($this->workContract, $this->attributes['text']);
    }

    /**
     * create callable the work contract content text type attribute
     *
     * @return string
     */
    public function getTextTypeDescription(): string
    {
        return WorkContractContentTextType::getDescription($this->attributes['text_type']);
    }
}
