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
        $url = uploadFile($photoFile, 'storage/uploads/employees');
        $this->attributes['photo_url'] = $url;
    }

    public function setStatusAttribute($_status)
    {
        $status = $this->findFromAttributes('EMPLOYEE_STATUSES', $_status);
        $this->attributes['status'] = $status['value'];
    }

    public function getEmploymentStatusLabelAttribute()
    {
        $_status = $this->attributes['employment_status'];
        $status = $this->findFromAttributes('EMPLOYEE_STATUSES', $_status);
        return $status['label'];
    }
}