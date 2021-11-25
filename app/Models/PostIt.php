<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class PostIt extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    
    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'post_its';

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
        'content',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'content',
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($postIt) {
            $postIt->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get company of the post it
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user that created the post it
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pivot table of the assigned user
     */
    public function assignedUsersPivot()
    {
        return $this->hasMany(PostItAssignedUser::class);
    }
    
    /**
     * Get the assigned users to this post it
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, PostItAssignedUser::class);
    }
}