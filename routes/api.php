<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'json'], function () {



    Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::group(['middleware'=>'auth:sanctum'],function (){

        Route::post('auth/profile', [\App\Http\Controllers\AuthController::class, 'profile']);

        Route::get('users',[\App\Http\Controllers\UserController::class,'all']);
        Route::post('users/store',[\App\Http\Controllers\UserController::class,'store']);
        Route::delete('users/{user}',[\App\Http\Controllers\UserController::class,'delete']);
        Route::get('managers/select',[\App\Http\Controllers\UserController::class,'managerSelect']);

        Route::post('material/allocation/sent',[\App\Http\Controllers\MaterialAllocationController::class,'sendMaterial']);
        Route::post('allocation/order',[\App\Http\Controllers\AllocationOrderController::class,'createOrder']);

        Route::get('inventory/stock',[\App\Http\Controllers\InventoryController::class,'stock']);
        Route::post('inventory/{id}/store',[\App\Http\Controllers\InventoryController::class,'storeMaterial']);

        Route::get('department/all',[\App\Http\Controllers\DepartmentController::class,'all']);
        Route::get('department/select',[\App\Http\Controllers\DepartmentController::class,'select']);
        Route::post('department/store',[\App\Http\Controllers\DepartmentController::class,'store']);
        Route::put('department/{department}/update',[\App\Http\Controllers\DepartmentController::class,'update']);
        Route::delete('department/{department}/delete',[\App\Http\Controllers\DepartmentController::class,'destroy']);
        Route::get('department/materials',[\App\Http\Controllers\DepartmentController::class,'departmentMaterials']);

        Route::get('supplier/all',[\App\Http\Controllers\SupplierController::class,'all']);
        Route::post('supplier/store',[\App\Http\Controllers\SupplierController::class,'store']);
        Route::put('supplier/{supplier}/update',[\App\Http\Controllers\SupplierController::class,'update']);
        Route::delete('supplier/{supplier}/delete',[\App\Http\Controllers\SupplierController::class,'destroy']);

        Route::get('category/all',[\App\Http\Controllers\CategoryController::class,'all']);
        Route::get('category/select',[\App\Http\Controllers\CategoryController::class,'categorySelect']);
        Route::post('category/store',[\App\Http\Controllers\CategoryController::class,'store']);
        Route::put('category/{category}/update',[\App\Http\Controllers\CategoryController::class,'update']);
        Route::delete('category/{category}/delete',[\App\Http\Controllers\CategoryController::class,'destroy']);

        Route::get('material/all',[\App\Http\Controllers\MaterialController::class,'all']);
        Route::post('material/store',[\App\Http\Controllers\MaterialController::class,'store']);
        Route::put('material/{id}/update',[\App\Http\Controllers\MaterialController::class,'update']);
        Route::delete('material/{material}/delete',[\App\Http\Controllers\MaterialController::class,'destroy']);
        Route::get('material/{material}/categories',[\App\Http\Controllers\MaterialController::class,'categories']);
        Route::get('material/select',[\App\Http\Controllers\MaterialController::class,'materialSelect']);
        Route::post('material/change-status/{material}',[\App\Http\Controllers\MaterialController::class,'changeStatus']);
        Route::get('material/locate/{material}',[\App\Http\Controllers\MaterialController::class,'locate']);

        Route::post('inventory/entry/{material}',[\App\Http\Controllers\InventoryController::class,'storeMaterial']);
        Route::delete('inventory/delete/{id}',[\App\Http\Controllers\InventoryController::class,'destroy']);
        Route::delete('inventory/stock',[\App\Http\Controllers\InventoryController::class,'stock']);

        Route::post('allocation-order/create',[\App\Http\Controllers\AllocationOrderController::class,'createOrder']);
        Route::get('allocation-orders/receive-orders',[\App\Http\Controllers\AllocationOrderController::class,'receiveOrderList']);
        Route::get('allocation-orders',[\App\Http\Controllers\AllocationOrderController::class,'orders']);
        Route::get('allocation-orders/{order}',[\App\Http\Controllers\AllocationOrderController::class,'lineItems']);
        Route::post('allocation-orders/{order}/change-status',[\App\Http\Controllers\AllocationOrderController::class,'updateStatus']);

        Route::get('purchase-orders',[\App\Http\Controllers\PurchaseOrderController::class,'all']);
        Route::post('purchase-order/create',[\App\Http\Controllers\PurchaseOrderController::class,'createOrder']);
        Route::get('purchase-order/line-items/{order}',[\App\Http\Controllers\PurchaseOrderController::class,'lineItems']);

        Route::post('material/request',[\App\Http\Controllers\MaterialRequestController::class,'all']);

        Route::post('auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

        Route::get('department/allocation-orders',[\App\Http\Controllers\AllocationOrderController::class,'departmentOrders']);

        Route::post('material/request',[\App\Http\Controllers\MaterialRequestController::class,'create']);
        Route::get('material/request/all',[\App\Http\Controllers\MaterialRequestController::class,'all']);
        Route::get('material/request/user',[\App\Http\Controllers\MaterialRequestController::class,'userRequest']);
        Route::get('material/request/{detail}',[\App\Http\Controllers\MaterialRequestController::class,'detailRequest']);

        Route::get('users/posts',[\App\Http\Controllers\PostController::class,'userPosts']);
        Route::post('manager/assign',[\App\Http\Controllers\PostController::class,'assignPost']);
    });


    Route::group(['middleware' => 'district-manager'], function () {

    });
    Route::group(['middleware' => 'purchaser'], function () {

    });
});

