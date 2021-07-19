<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class TaxSetting extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'tax_settings';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'include_tax',
        'tax_percentage',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($taxSetting) {
            $taxSetting->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}