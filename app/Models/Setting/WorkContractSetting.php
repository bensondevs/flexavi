<?php

namespace App\Models\Setting;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Models\Company\Company;
use App\Observers\WorkContractSettingObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WorkContractSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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
    protected $table = 'work_contract_settings';

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
    protected $fillable = ['company_id', 'footer'];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(WorkContractSettingObserver::class);
    }

    /**
     * Define relation of work contract setting contents type foreword
     *
     * @return HasMany
     */
    public function forewordContents(): HasMany
    {
        return $this->contents()->where(
            'position_type',
            WorkContractContentPositionType::Foreword
        );
    }

    /**
     * Define relation of work contract setting contents
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(
            WorkContractContentSetting::class,
            'work_contract_setting_id',
            'id'
        );
    }

    /**
     * Define relation of work contract setting contents type contract
     *
     * @return HasMany
     */
    public function contractContents(): HasMany
    {
        return $this->contents()->where(
            'position_type',
            WorkContractContentPositionType::Contract
        );
    }

    /**
     * Signature url attribute
     *
     * @return string|null
     */
    public function getSignatureUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('signature');
        return $media?->getFullUrl();
    }

    /**
     * Signature name attribute
     *
     * @return string|null
     */
    public function getSignatureNameAttribute(): ?string
    {
        $media = $this->getFirstMedia('signature');
        return $media?->name;
    }

    /**
     * Define relation of work contract setting company
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
