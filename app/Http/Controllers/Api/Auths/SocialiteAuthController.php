<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SocialiteAuthRepository;

class SocialiteAuthController extends Controller
{
    private $socialiteAuth;

    public function __construct(SocialiteAuthRepository $socialiteAuth)
    {
        $this->socialiteAuth = $socialiteAuth;
    }

    public function urlToVendor(Request $request)
    {
        $driver = $request->driver;
        $this->socialiteAuth->setDriver($driver);

        $url = $this->socialiteAuth->urlToVendor();
        return response()->json(['url' => $url]);
    }

    public function login(Request $request)
    {
        $driver = $request->driver;
        $this->socialiteAuth->setDriver($driver);
        $this->socialiteAuth->recieveCallback();

        $remember = boolval($request->remember);
        $this->socialiteAuth->login($remember);

        return apiResponse($this->socialiteAuth);
    }

    public function register(RegisterRequest $request)
    {
        $driver = $request->driver;
        $this->socialiteAuth->setDriver($driver);
        $this->socialiteAuth->recieveCallback();

        $input = $request->validated();
        $this->socialiteAuth->register($input);

        return apiResponse($this->socialiteAuth);
    }
}
