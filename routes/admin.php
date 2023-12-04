<?php

use App\Http\Controllers\Admin\Bill\BillController;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Buyer\BuyerController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GlassMM\GlassMMController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\Material\MaterialController;
use App\Http\Controllers\Admin\PriceMap\PriceMapController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Quotation\QuotationController;
use App\Http\Controllers\Admin\SubAdmin\SubAdminController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\TrnsactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'admin'],function(){
    Route::post('/login',[LoginController::class,'loginSubmit']);
    Route::get('/images/{filename}',[LoginController::class,'showImage'])->where('filename', '.*');
    Route::group(['middleware'=>'auth:sanctum'],function(){

        Route::get('dashboard',[DashboardController::class,'dashboard']);
        Route::post('change_password',[LoginController::class,'changePassword']);

        // SubAdmin
        Route::controller(SubAdminController::class)->prefix('sub_admin')->group(function(){
            Route::get('list','list');
            Route::post('add','add');
            Route::post('change_password','changePassword');
            Route::post('status','status');
        });

        // Brand
        Route::controller(BrandController::class)->prefix('brand')->group(function(){
            Route::get('list','list');
            Route::get('dropDown','dropDown');
            Route::post('add','add');
            Route::post('status','status');
        });


        // Glass MM
        Route::controller(GlassMMController::class)->prefix('glass_mm')->group(function(){
            Route::get('list','list');
            Route::get('dropDown','dropDown');
            Route::get('dropDownProduct','productDropDown');
            Route::post('add','add');
            Route::post('status','status');
        });


        // Category
        Route::controller(CategoryController::class)->prefix('category')->group(function(){
            Route::get('list','list');
            Route::get('dropDown','dropDown');
            Route::post('add','add');
            Route::post('status','status');
        });

        // Material
        Route::controller(MaterialController::class)->prefix('material')->group(function(){
            Route::get('list','list');
            Route::get('dropDown','dropDown');
            Route::get('dropDownProduct','productDropDown');
            Route::post('add','add');
            Route::post('status','status');
        });


        // Price Map
        Route::controller(PriceMapController::class)->prefix('price_map')->group(function(){
            Route::get('list','list');
            Route::post('add','add');
            Route::post('status','status');
        });


        // Product
        Route::controller(ProductController::class)->prefix('product')->group(function(){
            Route::get('list','list');
            Route::get('dropDown','dropDown');
            Route::post('fetch_price','fetch_price');
            Route::post('add','add');
            Route::post('status','status');
        });


        // Buyers
        Route::controller(BuyerController::class)->prefix('buyers')->group(function(){
            Route::get('list','list');
            Route::get('dropDown','dropDown');
            Route::post('add','add');
            Route::post('status','status');
            Route::post('fetch','fetch');
        });

        // Quotation
        Route::controller(QuotationController::class)->prefix('quotations')->group(function(){
            Route::get('list','list');
            Route::post('create','create');
            Route::post('fetch','fetch');
        });


        // Bill
        Route::controller(BillController::class)->prefix('bill')->group(function(){
            Route::get('list','list');
            Route::post('create','create');
            Route::post('fetch','fetch');
        });

        // Bill
        Route::group(['prefix'=>'report'],function(){
            Route::post('fetch',[ReportController::class,'fetch']);
        });

        Route::group(['prefix'=>'transaction'],function(){
            Route::post('fetch',[TrnsactionController::class,'fetch']);
        });
        // logout
        Route::post('/logout',[LoginController::class,'logout']);
    });


});
