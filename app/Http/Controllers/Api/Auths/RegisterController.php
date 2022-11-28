<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Auths\FindInvitationCodeRequest;
use App\Http\Requests\Company\Auths\RegisterRequest;
use App\Http\Resources\Invitation\RegisterInvitationResource;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Register service class container variable
     *
     * @var RegisterService
     */
    private RegisterService $registerService;

    /**
     * Controller constructor method
     *
     * @param RegisterService $registerService
     */
    public function __construct(
        RegisterService $registerService
    )
    {
        $this->registerService = $registerService;
    }

    /**
     * Register execution
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = [
            'user' => $request->userData(),
            'address' => $request->getAddressData(),
            'invitation' => $request->getInvitation(),
            'socialite' => $request->userSocialiteData(),
        ];
        if (isset($input['profile_picture'])) {
            $data['user']['profile_picture'] = $request->profile_picture;
        }

        $service = $this->registerService->handle($data);

        return apiResponse($service);
    }

    /**
     * Find invitation code
     *
     * @param FindInvitationCodeRequest $request
     * @return JsonResponse
     */
    public function findInvitationCode(FindInvitationCodeRequest $request): JsonResponse
    {
        $invitation = $request->getRegisterInvitation();
        return response()->json(['invitation' => new RegisterInvitationResource($invitation)]);
    }
}
