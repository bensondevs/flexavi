<?php

namespace App\Models\PostIt;

use App\Models\User\User;
use App\Observers\PostItAssignedUserObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PostItAssignedUser extends Model
{

    use HasFactory;
    use SoftDeletes;


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
    public $searchableFields = [];

    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = ['post_it_id', 'user_id'];

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
     *
     * @return BelongsTo
     */
    public function postIt()
    {
        return $this->belongsTo(PostIt::class);
    }

    /**
     * Get the user assigned to this pivot model
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
