<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Traits\SettingAttributes;
use App\Enums\Setting\SettingType;
use App\Enums\SettingValue\SettingValueType as ValueType;
use App\Observers\SettingObserver as Observer;

class Setting extends Model
{
    use Searchable;
    use SettingAttributes;

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
    protected $searchable = [
        'type',
        'key',

        'input_type',
        'options',

        'value_data_type',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'type',
        'key',
        'value_data_type',
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
     * Create settable attribute of "options_array"
     * This settable attribute will set the options json value using array
     * 
     * @param  array  $optionsArray
     * @return void
     */
    public function setOptionsArrayAttribute(array $optionsArray)
    {
        $json = json_encode($optionsArray);
        $this->attributes['options'] = $json;
    }

    /**
     * Get setting values
     */
    public function values()
    {
        return $this->hasMany(SettingValue::class);
    }

    /**
     * Find setting by key
     * 
     * @param  string  $key
     * @param  bool  $abortIfFail 
     * @return self
     */
    public static function findByKey(string $key, bool $abortIfFail = false)
    {
        $query = self::where('key', $key);
        return $abortIfFail ? $query->firstOrFail() : $query->first();
    }

    /**
     * Find setting by key and abort 404 fail if not found
     * 
     * @param  string  $key
     * @return self
     */
    public static function findByKeyOrFail(string $key)
    {
        return self::findByKey($key, true);
    }

    /**
     * Get value
     * 
     * @return  \App\Models\SettingValue
     */
    public function getValue()
    {
        return $this->getCompanyValue() ?:
            $this->getDefaultValue();
    }

    /**
     * Get default setting value
     * 
     * @return \App\Models\SettingValue
     */
    public function getDefaultValue()
    {
        return $this->values
            ->where('value_type', ValueType::Default)
            ->firstOrFail();
    }

    /**
     * Get default casted setting value
     * 
     * @return mixed
     */
    public function getDefaultCastedValue()
    {
        $value = $this->getDefaultValue();
        $dataType = $this->attributes['value_data_type'];
        return cast_string($value->value, $dataType);
    }

    /**
     * Get company setting value
     * 
     * @param  \App\Models\Company  $company
     * @return \App\Models\SettingValue
     */
    public function getCompanyValue(Company $company)
    {
        return $this->values
            ->where('value_type', ValueType::Company)
            ->where('company_id', $company->id)
            ->first();
    }

    /**
     * Get company casted setting value
     * 
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function getCompanyCastedValue(Company $company)
    {
        $value = $this->getCompanyValue($company) ?:
            $this->getDefaultValue();
        $dataType = $this->attributes['value_data_type'];
        return cast_string($value->value, $dataType);
    }

    /**
     * Set default setting value
     * 
     * @param  mixed  $value
     * @return bool
     */
    public function setDefaultValue($value)
    {
        $defaultValue = $this->getDefaultValue();
        $defaultValue->value = $value;
        return $defaultValue->save();
    }

    /**
     * Set company setting value
     * 
     * @param  mixed  $value
     * @param  \App\Models\Company  $company
     * @return bool
     */
    public function setCompanyValue($value, Company $company)
    {
        $companyValue = $this->getCompanyValue();
        $companyValue->value = $value;
        return $companyValue->save();
    }
}