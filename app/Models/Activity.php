<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($activity) {
            $activity->id = Uuid::generate()->string;
            $activity->company_id = auth()->user()->owner->company->id;
    	});
    }
}