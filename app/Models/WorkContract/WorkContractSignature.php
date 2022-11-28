<?php

namespace App\Models\WorkContract;

use App\Enums\Setting\WorkContract\WorkContractSignatureType;
use App\Observers\WorkContractSignatureObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WorkContractSignature extends Model implements HasMedia
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
    protected $table = 'work_contract_signatures';

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
        'work_contract_id',
        'name',
        'type',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(WorkContractSignatureObserver::class);
    }

    /**
     * Define the work contract relationship
     *
     * @return BelongsTo
     */
    public function workContract(): BelongsTo
    {
        return $this->belongsTo(WorkContract::class, 'work_contract_id', 'id');
    }

    /**
     * Define the work contract signature type attribute
     *
     * @return string|null
     */
    public function getTypeDescriptionAttribute(): ?string
    {
        $type = $this->attributes['type'];
        if (!$type) {
            return null;
        }

        return WorkContractSignatureType::getDescription($type);
    }

    /**
     * Define the work contract signature url attribute
     *
     * @return string|null
     */
    public function getSignatureUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('signature');
        return $media?->getFullUrl();
    }

}
