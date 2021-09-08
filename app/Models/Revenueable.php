<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Revenueable extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'revenueables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [

    ];

    protected $fillable = [
        'revenue_id',

        'revenueable_type',
        'revenueable_id',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($revenueable) {
            $revenueable->id = Uuid::generate()->string;
    	});
    }

    public function revenue()
    {
        return $this->belongsTo(Revenue::class);
    }

    public function revenueable()
    {
        return $this->morphTo();
    }
}