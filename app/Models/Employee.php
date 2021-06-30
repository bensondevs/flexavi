<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Traits\ModelEnums;

class Employee extends Model
{
    use ModelEnums;
    use SoftDeletes;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const EMPLOYEE_TYPES = [
        [
            'label' => 'Administrative',
            'value' => 'administrative',
        ],
        [
            'label' => 'Roofer',
            'value' => 'roofer',
        ]
    ];

    const EMPLOYEE_STATUSES = [
        [
            'label' => 'Active',
            'value' => 'active',
        ],
        [
            'label' => 'Inactive',
            'value' => 'inactive',
        ],
        [
            'label' => 'Fired',
            'value' => 'fired',
        ]
    ];

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'employee_type',
        'employment_status',

        'address',
        'house_number',
        'house_number_suffix',
        'zipcode',
        'city',
        'province',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($employee) {
            $employee->id = Uuid::generate()->string;
    	});
    }

    public function user()
    {
        return $this->belongsTo(
            'App\Models\User', 
            'user_id', 
            'id'
        );
    }

    public function company()
    {
        return $this->hasOne(
            'App\Models\Company', 
            'id', 
            'company_id'
        );
    }

    public function setPhotoAttribute($photoFile)
    {
        // Upload Photo
        $photo = uploadFile($photoFile, 'uploads/employees');
        $this->attributes['photo_path'] = $photo->path;
    }

    public function getEmploymentStatusLabelAttribute()
    {
        $statuses = collect(self::EMPLOYEE_STATUSES);
        $statuses = $statuses->where('value', $this->attributes['employment_status']);
        $status = $statuses->first();

        return $status['label'];
    }

    public static function getTypeValues()
    {
        $types = collect(self::EMPLOYEE_TYPES);
        $values = $types->pluck('value');

        return $values->toArray();
    }

    public static function getStatusValues()
    {
        $types = collect(self::EMPLOYEE_STATUSES);
        $values = $types->pluck('value');

        return $values->toArray();
    }
}