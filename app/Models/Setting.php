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
        'key',
        'value',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'type',
        'key',
        'value',
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

    	self::creating(function ($setting) {
            $setting->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get all default settings
     * 
     * @static
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function defaults()
    {
        return self::where('type', SettingType::Default)->get();
    }

    /**
     * Get company settings
     * 
     * @static
     * @param \App\Models\Company  $company
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function ofCompany(Company $company)
    {
        $defaultSettings = self::defaults();
        $companySettings = self::where('settingable_type', Company::class)
            ->where('settingable_id', $company->id)
            ->get();
        return $defaultSettings->merge($companySettings);
    }
}