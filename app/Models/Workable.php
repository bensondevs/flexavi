<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Workable extends Model
{
    protected $table = 'workables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'workable_type',
        'workable_id',
    ];

    protected $fillable = [

    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workable) {
            $workable->id = Uuid::generate()->string;
    	});
    }

    public static function isAlreadyAttached(Work $work, $workable)
    {
        $workableType = get_class($workable);

        return self::where('work_id', $work->id)
            ->where('workable_type', $workableType)
            ->where('workable_id', $workable->id)
            ->count() > 0;
    }
}