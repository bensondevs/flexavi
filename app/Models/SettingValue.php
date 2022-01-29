<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SettingValueObserver as Observer;

class SettingValue extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'setting_values';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set timestamp each time model is saved
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use incrementing value or not
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
        'value',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'setting_id',
        'company_id',
        'value_type',
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
        self::observe(Observer::class);
    }

    /**
     * Create callable static method of "forSetting(Setting $setting)"
     * This callable static method will query only setting value
     * that set the value for certain setting based on 
     * specified value on the parameter
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSetting(Builder $query, Setting $setting)
    {
        return $query->where('setting_id', $setting->id);
    }

    /**
     * Create callable static method of "forCompany(Company $company)"
     * This callable static method will query only setting value
     * that has been set by and for a certain company.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCompany(Builder $query, Company $company)
    {
        return $query->where('company_id', $company->id);
    }

    /**
     * Get the setting configured by current value
     */
    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }

    /**
     * Get the company that do the setting
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}