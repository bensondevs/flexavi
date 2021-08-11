<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Customer\CustomerController;

Route::get('current', [CustomerController::class, 'current']);