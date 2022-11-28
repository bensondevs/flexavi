<?php

namespace App\Models\Setting;

use App\Enums\Setting\SettingModule;
use App\Models\Company\Company;
use App\Observers\SettingObserver as Observer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
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
    public array $searchableFields = [
        'module',
        'json',
    ];
    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'settings';
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
    protected $fillable = ['company_id', 'module', 'key', 'value'];

    /**
     * Static method to find value by company_id, module and key
     *
     * @param array $wheres
     * @return mixed
     */
    public static function findValueOf(array $wheres): mixed
    {
        /**
         *  inside this method is not safe error so make sure the required parameters are not empty
         *
         *  required parameters ['module' , 'key']
         *  optional parameter ['company_id' ]
         */

        $value = tryIsset(
            fn() => Setting::query()
                ->when($wheres['company_id'] ?? null, fn($q) => $q->whereCompanyId($wheres['company_id']))
                ->module($wheres['module'])
                ->key($wheres['key'])
                ->first()->value
        );

        // Default value by database (Dynamic)
        if (is_null($value)) {
            $value = tryIsset(
                fn() => Setting::query()
                    ->whereCompanyId(null)
                    ->module($wheres['module'])
                    ->key($wheres['key'])
                    ->first()->value
            );
        }

        // Default value by enum (Static)
        if (is_null($value)) {
            $module = getConstantName(SettingModule::class, $wheres['module']);

            $keyEnumClass = ("\App\Enums\Setting\\$module\\$module" . "SettingKey");
            $key = getConstantName($keyEnumClass, $wheres['key']);

            $valueEnumClass = ("\App\Enums\Setting\\$module\\$module" . "$key");
            $value = $valueEnumClass::Default;
        }

        return $value;
    }

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
        self::observe(Observer::class);
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
     * Create callable "module_description" attribute
     * This callable attribute will return description of SettingModule enum
     *
     * @return string
     */
    public function getModuleDescriptionAttribute(): string
    {
        $module = $this->attributes['module'];
        return SettingModule::getDescription($module);
    }

    /**
     * Create callable "key_description" attribute
     * This callable attribute will return description of SettingModule enum
     *
     * @return string
     */
    public function getKeyDescriptionAttribute(): string
    {
        $module = getConstantName(
            SettingModule::class,
            $this->attributes['module']
        );
        $enumClass = '\App\Enums\Setting' . "\\$module\\$module" . 'SettingKey';
        $key = $this->attributes['key'];
        return $enumClass::getDescription($key);
    }

    /**
     * Scope a query for getting default setting of related module.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('company_id', null);
    }

    /**
     * Scope a query by module.
     *
     * @param Builder $query
     * @param string $module
     * @return Builder
     */
    public function scopeModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    /**
     * Scope a query by key.
     *
     * @param Builder $query
     * @param string $key
     * @return Builder
     */
    public function scopeKey(Builder $query, string $key): Builder
    {
        return $query->where('key', $key);
    }

    /**
     * The setting value
     *
     * @return Attribute
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                try {
                    return json_decode($value);
                } catch (\Exception $e) {
                    return $value;
                }
            },
            set: function ($value) {
                try {
                    return json_encode($value);
                } catch (\Exception $e) {
                    return $value;
                }
            }
        );
    }
}
