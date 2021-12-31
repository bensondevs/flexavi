<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\PostItAssignedUserObserver as Observer;

class PostItAssignedUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'post_it_assigned_users';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'post_it_id',
        'user_id',
    ];

    /**
     * Function that will be run whenever event happened
     * 
     * @return  void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Get the post it
     */
    public function postIt()
    {
        return $this->belongsTo(PostIt::class);
    }

    /**
     * Get the user assigned to this pivot model 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}