<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Work extends Model
{
    protected $table = 'works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'work_contract_id',
        'name',
        'description',
        'price',
        'include_tax',
        'tax',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($work) {
            $work->id = Uuid::generate()->string;
    	});
    }

    public function conditionPhotos()
    {
        return $this->hasMany(
            'App\Models\WorkConditionPhoto',
            'work_id',
            'id'
        );
    }
}