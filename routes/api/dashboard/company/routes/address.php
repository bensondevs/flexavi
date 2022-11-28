<?php

use App\Http\Controllers\Api\Company\Address\{AddressController,
    CustomerAddressController,
    EmployeeAddressController,
    OwnerAddressController
};


/**
 * Address module
 */
Route::group(['prefix' => 'addresses'], function () {
    Route::get('/', [AddressController::class, 'companyAddresses']);
    Route::get('trasheds', [
        AddressController::class,
        'companyTrashedAddresses',
    ]);
    Route::post('store', [AddressController::class, 'store']);
    Route::get('view', [AddressController::class, 'view']);
    Route::match(['PUT', 'PATCH'], 'update', [
        AddressController::class,
        'update',
    ]);
    Route::delete('delete', [AddressController::class, 'delete']);
    Route::patch('restore', [AddressController::class, 'restore']);

    /**
     * Customer address module
     */
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', [
            CustomerAddressController::class,
            'customerAddresses',
        ]);
        Route::get('trasheds', [
            CustomerAddressController::class,
            'customerTrashedAddresses',
        ]);
        Route::post('store', [CustomerAddressController::class, 'store']);
        Route::get('view', [CustomerAddressController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [
            CustomerAddressController::class,
            'update',
        ]);
        Route::delete('delete', [
            CustomerAddressController::class,
            'delete',
        ]);
        Route::patch('restore', [
            CustomerAddressController::class,
            'restore',
        ]);
    });

    /**
     * Owner address module
     */
    Route::group(['prefix' => 'owner'], function () {
        Route::get('/', [OwnerAddressController::class, 'ownerAddresses']);
        Route::get('trasheds', [
            OwnerAddressController::class,
            'ownerTrashedAddresses',
        ]);
        Route::post('store', [OwnerAddressController::class, 'store']);
        Route::get('view', [OwnerAddressController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [
            OwnerAddressController::class,
            'update',
        ]);
        Route::delete('delete', [OwnerAddressController::class, 'delete']);
        Route::patch('restore', [OwnerAddressController::class, 'restore']);
    });

    /**
     * Employee address module
     */
    Route::group(['prefix' => 'employee'], function () {
        Route::get('/', [
            EmployeeAddressController::class,
            'employeeAddresses',
        ]);
        Route::get('trasheds', [
            EmployeeAddressController::class,
            'employeeTrashedAddresses',
        ]);
        Route::post('store', [EmployeeAddressController::class, 'store']);
        Route::get('view', [EmployeeAddressController::class, 'view']);
        Route::match(['PUT', 'PATCH'], 'update', [
            EmployeeAddressController::class,
            'update',
        ]);
        Route::delete('delete', [
            EmployeeAddressController::class,
            'delete',
        ]);
        Route::patch('restore', [
            EmployeeAddressController::class,
            'restore',
        ]);
    });
});
