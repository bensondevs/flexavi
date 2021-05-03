<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\User\SaveUserRequest;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\SetProfilePictureRequest;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $userRepository)
    {
    	$this->user = $userRepository;
    }

    public function current()
    {
    	return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function setProfilePicture(SetProfilePictureRequest $request)
    {
        $this->user->setModel($request->user());
        $user = $this->user->setProfilePicture(
            $request->file('profile_picture')
        );

        return apiResponse(
            $this->user, 
            ['profile_picture' => $user->profile_picture]
        );
    }

    public function update(SaveUserRequest $request)
    {
        $this->user->setModel($request->user());
        $this->user->save($request->onlyInRules());

        return apiResponse(
            $this->user, 
            $this->user->getModel()
        );
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->user->setModel($request->user());
        $this->user->changePassword($request->password);

        return apiResponse(
            $this->user, 
            $this->user->getModel()
        );
    }
}
