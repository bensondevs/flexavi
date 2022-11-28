<?php

namespace App\Models\Setting;

use App\Enums\Setting\EmployeeSetting\EmployeeInvitationExpiry;
use App\Models\Company\Company;
use App\Traits\DefaultSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSetting extends Model
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
    protected $table = 'employee_settings';
    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'pagination',
        'use_initials_when_dont_have_avatar',
        'invitation_expiry',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'use_initials_when_dont_have_avatar' => 'bool',
    ];
    /**
    * Define auto appended attributes on model load
    *
    * @var bool
    */
    protected $appends = ['invitation_expiry_description'];

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

     /**
     * Create callable "invitation_expiry_description" attribute
     * This callable attribute will return status enum description
     *
     * @return string
     */
    public function getInvitationExpiryDescriptionAttribute()
    {
        return EmployeeInvitationExpiry::getDescription(
            $this->attributes['invitation_expiry']
        );
    }
}
