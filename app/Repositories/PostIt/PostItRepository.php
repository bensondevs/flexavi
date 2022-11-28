<?php

namespace App\Repositories\PostIt;

use App\Models\{PostIt\PostIt, PostIt\PostItAssignedUser, User\User};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PostItRepository extends BaseRepository
{
    /**
     * Create New Repository Instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new PostIt());
    }

    /**
     * Save to database
     *
     * @param  array $postItData
     * @return PostIt|null
     */
    public function save(array $postItData = [])
    {
        try {
            $postIt = $this->getModel();
            $postIt->fill($postItData);
            $postIt->user_id = auth()->user()->id;
            $postIt->save();
            $this->setModel($postIt);
            $this->setSuccess('Successfully save post it.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save post it.', $error);
        }

        return $this->getModel();
    }

    /**
     * Assign user to this post it
     *
     * @param  User $user
     * @return PostIt|null
     */
    public function assignUser(User $user)
    {
        try {
            $postIt = $this->getModel();
            PostItAssignedUser::create([
                'post_it_id' => $postIt->id,
                'user_id' => $user->id,
            ]);
            $this->setModel($postIt);
            $this->setSuccess('Successfully assign user to post it.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign user to post it.', $error);
        }

        return $this->getModel();
    }

    /**
     * Restore post it form trash
     *
     * @return bool
     */
    public function restore()
    {
        try {
            $postIt = $this->getModel();
            $postIt->restore();
            $this->setSuccess('Successfully restore the post it.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore post it.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Unassign user from this post it
     *
     * @param  PostItAssignedUser $pivot
     * @return PostIt|null
     */
    public function unassignUser(PostItAssignedUser $pivot)
    {
        try {
            $pivot->delete();
            $this->setSuccess('Successfully unassign user from the post it.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to unassign user from post it.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Delete post it soft or hard
     *
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $postIt = $this->getModel();
            $force ? $postIt->forceDelete() : $postIt->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete post it.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete post it.', $error);
        }

        return $this->returnResponse();
    }
}
