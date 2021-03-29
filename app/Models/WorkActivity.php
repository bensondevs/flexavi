<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class WorkActivity extends Model
{
    protected $table = 'work_activities';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'assignable_type',
        'assignable_id',
        'activity_name',
        'price',
        'unit',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workActivity) {
            $workActivity->id = Uuid::generate()->string;
    	});
    }

    public function assigned()
    {
        return $this->belongsTo(
            $this->attributes['assignable_type'],
            'id',
            'assignable_id'
        );
    }
}