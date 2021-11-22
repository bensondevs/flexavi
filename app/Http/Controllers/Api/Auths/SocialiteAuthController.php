<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SocialiteAuthRepository;

class SocialiteAuthController extends Controller
{
    /**
     * Socialite auth repository class
     * 
     * @var \App\Repositories\SocialiteAuthRepository
     */
    private $socialiteAuth;

    /**
     * Controller constructor method
     * 
     * @param App\Repositories\SocialiteAuthRepository  $socialiteAuth
     * @return void
     */
    public function __construct(SocialiteAuthRepository $socialiteAuth)
    {
        $this->socialiteAuth = $socialiteAuth;
    }

    /**
     * Get url to vendor depends on the request
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function urlToVendor(Request $request)
    {
        $driver = $request->driver;
        $this->socialiteAuth->setDriver($driver);

        $url = $this->socialiteAuth->urlToVendor();
        return response()->json(['url' => $url]);
    }

    /**
     * Receive callback from vendor and attempt login
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function login(Request $request)
    {
        $driver = $request->driver;
        $this->socialiteAuth->setDriver($driver);
        $this->socialiteAuth->recieveCallback();

        $remember = boolval($request->remember);
        $this->socialiteAuth->login($remember);

        return apiResponse($this->socialiteAuth);
    }

    /**
     * Receive callback from vendor and attemp register
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
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
