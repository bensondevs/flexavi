<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\{ PostIt, PostItAssignedUser, User };

class PostItRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new PostIt);
	}

	/**
	 * Save to database
	 * 
	 * @param  array  ?$postItData
	 * @return \App\Models\PostIt  $postIt
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
	 * @param  \App\Models\User
	 * @return \App\Models\PostIt  $postIt
	 */
	public function assignUser(User $user)
	{
		try {
			$postIt = $this->getModel();
			$pivot = PostItAssignedUser::create([
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
	 * Unassign user from this post it
	 * 
	 * @param  \App\Models\PostItAssignedUser $pivot
	 * @return \App\Models\PostIt  $postIt
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
	 * @param  array  $force
	 * @return \App\Models\PostIt  $postIt
	 */
	public function delete(bool $force = false)
	{
		try {
			$postIt = $this->getModel();
			$force ?
				$postIt->forceDelete() :
				$postIt->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete post it.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete post it.', $error);
		}

		return $this->returnResponse();
	}
}
