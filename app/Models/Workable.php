<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Workable extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'workables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [

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
}