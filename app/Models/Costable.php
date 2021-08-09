<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Costable extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'costables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;

    protected static function boot()
    {
    	parent::boot();
    }

    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    public function costable()
    {
        return $this->morphTo();
    }
}