<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use Spatie\Activitylog\Models\Activity;

class Activity extends Activity
{
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($activity) {
            $activity->id = Uuid::generate()->string;
    	});
    }
}