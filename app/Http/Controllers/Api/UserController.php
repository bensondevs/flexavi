<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Users\SaveUserRequest;
use App\Http\Requests\Company\Users\SetProfilePictureRequest;
use App\Http\Requests\Users\{ChangePasswordRequest};
use App\Http\Resources\Users\UserResource;
use App\Repositories\User\UserRepository;

class UserController extends Controller
{
    /**
     * User Repository class container
     *
     * @var App\Repositories\UserRepository
     */
    private $user;

    /**
     * Controller constructor method
     *
     * @param App\Repositories\UserRepository  $user
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Get current requesting user
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function current()
    {
        $user = new UserResource(auth()->user());
        return response()->json(['user' => $user]);
    }

    /**
     * Set user profile picture
     *
     * @param App\Http\Requests\Users\SetProfilePictureRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function setProfilePicture(SetProfilePictureRequest $request)
    {
        $user = $request->user();
        $this->user->setModel($user);

        $picture = $request->profile_picture;
        $user = $this->user->setProfilePicture($picture);

        return apiResponse($this->user, ['profile_picture' => $user->profile_picture]);
    }

    /**
     * Update user
     *
     * @param App\Http\Requests\Users\SaveUserRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveUserRequest $request)
    {
        $user = $request->user();
        $user = $this->user->setModel($user);

        $input = $request->onlyInRules();
        $user = $this->user->save($input);

        return apiResponse($this->user, ['user' => $user]);
    }

    /**
     * Change user password
     *
     * @param App\Http\Requests\Users\ChangePasswordRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $user = $this->user->setModel($user);

        $password = $request->input('password');
        $user = $this->user->changePassword($password);

        return apiResponse($this->user, ['user' => $user]);
    }
}
