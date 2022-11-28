<?php

namespace App\Models\Setting;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Observers\WorkContractContentSettingObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkContractContentSetting extends Model
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
    protected $table = 'work_contract_content_settings';

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
        'work_contract_setting_id',
        'order_index',
        'position_type',
        'text_type',
        'text',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(WorkContractContentSettingObserver::class);
        self::addGlobalScope('orderIndex', function (Builder $builder) {
            $builder->orderBy('order_index', 'asc');
        });
    }

    /**
     * Define the work contract setting relationship
     *
     * @return BelongsTo
     */
    public function workContractSetting(): BelongsTo
    {
        return $this->belongsTo(WorkContractSetting::class, 'work_contract_setting_id', 'id');
    }

    /**
     * Define the work contract content setting position type enum
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeForeword(Builder $builder): Builder
    {
        return $builder->where('position_type', WorkContractContentPositionType::Foreword);
    }

    /**
     * Define the work contract content setting position type enum
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeContracts(Builder $builder): Builder
    {
        return $builder->where('position_type', WorkContractContentPositionType::Contract);
    }

    /**
     * create callable the work contract content position type attribute
     *
     * @return string
     */
    public function getPositionTypeDescriptionAttribute(): string
    {
        return WorkContractContentPositionType::getDescription($this->attributes['position_type']);
    }

    /**
     * create callable the work contract content text type attribute
     *
     * @return string
     */
    public function getTextTypeDescriptionAttribute(): string
    {
        return WorkContractContentTextType::getDescription($this->attributes['text_type']);
    }
}
