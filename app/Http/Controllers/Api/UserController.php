<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\ChangeProfilePictureRequest;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $userRepository)
    {
    	$this->user = $userRepository;
    }

    public function currentUser()
    {
    	return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function updateUser(UpdateUserRequest $request)
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

    public function changeProfilePicture(ChangeProfilePictureRequest $request)
    {
        $this->user->setModel($request->user());
        $this->user->changeProfilePicture(
            $request->file('profile_picture')
        );

        return apiResponse(
            $this->user, 
            $this->user->getModel()
        );
    }
}
