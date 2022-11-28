<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class AdminController extends Controller
{
    private $user;

    public function __construct(UserRepository $user)
    {
    	$this->user = $user;
    }

    public function current()
    {
    	return response()->json(['admin' => auth()->user()]);
    }
}
