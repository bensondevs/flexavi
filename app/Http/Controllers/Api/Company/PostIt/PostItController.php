<?php

namespace App\Http\Controllers\Api\Company\PostIt;

use App\Http\Controllers\Api\Company\AssignRequest;
use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\PostIts\{UnassignPostItUserRequest as UnassignUserRequest};
use App\Http\Requests\Company\PostIts\AssignPostItUserRequest as AssignUserRequest;
use App\Http\Requests\Company\PostIts\DeletePostItRequest as DeleteRequest;
use App\Http\Requests\Company\PostIts\PopulateCompanyPostItsRequest as PopulateRequest;
use App\Http\Requests\Company\PostIts\RestorePostItRequest as RestoreRequest;
use App\Http\Requests\Company\PostIts\SavePostItRequest as SaveRequest;
use App\Http\Resources\PostIt\PostItResource;
use App\Repositories\PostIt\PostItRepository;

class PostItController extends Controller
{
    /**
     * Post It Repository Container
     *
     * @var PostItRepository
     */
    private $postIt;

    /**
     * Controller construct instace
     *
     * @param PostItRepository
     * @return void
     */
    public function __construct(PostItRepository $postIt)
    {
        $this->postIt = $postIt;
    }

    /**
     * Populate company post its
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function companyPostIts(PopulateRequest $request)
    {
        $options = $request->options();

        $postIts = $this->postIt->all($options, true);
        $postIts = PostItResource::apiCollection($postIts);

        return response()->json(['post_its' => $postIts]);
    }

    /**
     * Store company post it
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $this->postIt->save($input);
        return apiResponse($this->postIt);
    }

    /**
     * Update company post it
     *
     * @param SaveRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $postIt = $request->getPostIt();
        $this->postIt->setModel($postIt);

        $input = $request->validated();
        $this->postIt->save($input);
        return apiResponse($this->postIt);
    }

    /**
     * Assign post it to a user
     *
     * @param AssignUserRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function assignUser(AssignUserRequest $request)
    {
        $postIt = $request->getPostIt();
        $this->postIt->setModel($postIt);

        $user = $request->getAssignedUser();
        $this->postIt->assignUser($user);

        return apiResponse($this->postIt);
    }

    /**
     * Unassign post it to a user
     *
     * @param AssignRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function unassignUser(UnassignUserRequest $request)
    {
        $pivot = $request->getAssignedUserPivot();
        $this->postIt->unassignUser($pivot);
        return apiResponse($this->postIt);
    }

    /**
     * Delete post it
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $postIt = $request->getPostIt();
        $this->postIt->setModel($postIt);

        $force = $request->input('force');
        $this->postIt->delete($force);

        return apiResponse($this->postIt);
    }

    /**
     * Restore post it
     *
     * @param DeleteRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $postIt = $request->getPostIt();
        $this->postIt->setModel($postIt);

        $this->postIt->restore();

        return apiResponse($this->postIt);
    }
}
