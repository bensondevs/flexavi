<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\User\SaveUserRequest;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\SetProfilePictureRequest;

use App\Enums\User\UserIdCardType;

use App\Repositories\UserRepository;

use App\Http\Resources\UserResource;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $userRepository)
    {
    	$this->user = $userRepository;
    }

    public function current()
    {
        $user = auth()->user();
    	return response()->json(['user' => new UserResource($user)]);
    }

    public function idCardTypes()
    {
        $types = UserIdCardType::asSelectArray();
        return response()->json($types);
    }

    public function setProfilePicture(SetProfilePictureRequest $request)
    {
        $user = $request->user();
        $this->user->setModel($user);

        $picture = $request->profile_picture;
        $user = $this->user->setProfilePicture($picture);

        return apiResponse($this->user, ['profile_picture' => $user->profile_picture]);
    }

    public function update(SaveUserRequest $request)
    {
        $user = $request->user();
        $user = $this->user->setModel($user);

        $input = $request->onlyInRules();
        $user = $this->user->save($input);

        return apiResponse($this->user, ['user' => $user]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $user = $this->user->setModel($user);

        $password = $request->input('password');
        $user = $this->user->changePassword($password);

        return apiResponse($this->user, ['user' => $user]);
    }
}
