<?php

namespace App\Models\PostIt;

use App\Models\Company\Company;
use App\Models\User\User;
use App\Observers\PostItObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};


class PostIt extends Model
{

    use HasFactory;
    use SoftDeletes;


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
    public $searchableFields = ['content'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'content'];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Get company of the post it
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user that created the post it
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pivot table of the assigned user
     *
     * @return HasMany
     */
    public function assignedUsersPivot()
    {
        return $this->hasMany(PostItAssignedUser::class);
    }

    /**
     * Get the assigned users to this post it
     *
     * @return BelongsToMany
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(
            User::class,
            PostItAssignedUser::class
        )->whereNull('post_it_assigned_users.deleted_at');
    }

    /**
     * Assign user to current post it
     *
     * @param  User  $user
     * @return bool
     */
    public function assignUser(User $user)
    {
        $postItAssignedUser = new PostItAssignedUser();
        $postItAssignedUser->post_it_id = $this->attributes['id'];
        $postItAssignedUser->user_id = $user->id;

        return $postItAssignedUser->save();
    }
}
