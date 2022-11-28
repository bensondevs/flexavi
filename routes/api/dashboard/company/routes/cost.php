<?php


use App\Http\Controllers\Api\Company\Cost\CostController;
use App\Http\Controllers\Api\Company\Receipt\CostReceiptController;
use Illuminate\Support\Facades\Route ;

// @todo Hidden feature for next release
// TODO: Hidden feature for next release

/**
 * Company Cost Module
 */
/*
Route::group(['prefix' => 'costs'], function () {
   Route::get('/', [CostController::class, 'companyCosts']);
   Route::post('store', [CostController::class, 'store']);
   Route::get('view', [CostController::class, 'view']);
   Route::match(['PUT', 'PATCH'], 'update', [
       CostController::class,
       'update',
   ]);
   Route::delete('delete', [CostController::class, 'delete']);
   Route::patch('restore', [CostController::class, 'restore']);

   Route::group(['prefix' => 'receipt'], function () {
       Route::post('attach', [CostReceiptController::class, 'attach']);
       Route::post('replace', [CostReceiptController::class, 'replace']);
   });
});


*/
