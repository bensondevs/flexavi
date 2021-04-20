<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Inspector extends Model
{
    protected $table = 'inspectors';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'inspection_id',
        'user_id',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($inspector) {
            $inspector->id = Uuid::generate()->string;
    	});
    }

    public function user()
    {
        return $this->hasOne(
            'App\Models\User',
            'user_id',
            'id'
        );
    }
}