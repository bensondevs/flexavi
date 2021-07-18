<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('https://app.daksoftware.nl');
});

Route::get('test', function () {
    $now = carbon()->now()->copy();
    dd(\App\Models\Invoice::where('created_at', '>=', $now->startOfYear())
            ->where('status', '>=', \App\Enums\Invoice\InvoiceStatus::Sent)
            ->whereNotNull('invoice_number')
            ->count());
});