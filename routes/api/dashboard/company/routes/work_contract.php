<?php

use App\Http\Controllers\Api\Company\WorkContract\{WorkContractController, WorkContractSignedDocumentController};
use Illuminate\Support\Facades\Route;

/**
 * Company Work Contracts
 */
Route::group(['prefix' => 'work_contracts'], function () {
    Route::get('/', [WorkContractController::class, 'companyWorkContracts']);
    Route::get('trasheds', [WorkContractController::class, 'trashedWorkContracts']);
    Route::post('draft', [WorkContractController::class, 'draft']);
    Route::post('send', [WorkContractController::class, 'send']);
    Route::post('print', [WorkContractController::class, 'print']);
    Route::get('view', [WorkContractController::class, 'view']);
    Route::get('preview', [WorkContractController::class, 'preview']);
    Route::match(['PUT', 'PATCH'], 'update', [WorkContractController::class, 'update']);

    Route::post('use_company_format', [WorkContractController::class, 'useCompanyFormat']);
    Route::post('set_as_default_format', [WorkContractController::class, 'setAsDefaultFormat']);
    Route::patch('restore', [WorkContractController::class, 'restore']);
    Route::patch('nullify', [WorkContractController::class, 'nullify']);
    Route::delete('delete', [WorkContractController::class, 'delete']);

    Route::get('variables', [WorkContractController::class, 'variables']);

    Route::group(['prefix' => 'signed_documents'], function () {
        Route::post('upload', [WorkContractSignedDocumentController::class, 'uploadSignedDocument']);
        Route::delete('remove', [WorkContractSignedDocumentController::class, 'removeSignedDocument']);
    });
});
