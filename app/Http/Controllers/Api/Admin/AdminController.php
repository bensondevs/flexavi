<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\UserRepository;

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
