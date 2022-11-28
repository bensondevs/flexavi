<?php

namespace App\Models\Setting;

use App\Models\Company\Company;
use App\Traits\DefaultSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySetting extends Model
{
    use DefaultSetting;

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
    protected $table = 'company_settings';
    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Cast model attributes
     *
     * @var array
     */
    protected $casts = [
        'auto_subs_same_plan_while_ends' => 'boolean',
        'invoicing_address_same_as_visiting_address' => 'boolean',
    ];
    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'auto_subs_same_plan_while_ends',
        'invoicing_address_same_as_visiting_address'
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($setting) {
            $setting->id = generateUuid();
        });
    }

    /**
     * Get company of the quotation
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
