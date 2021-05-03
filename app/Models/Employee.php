<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'employee_type',
        'employee_status',
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
}