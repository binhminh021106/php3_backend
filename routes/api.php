<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Import Admin
 */

use App\Http\Controllers\Api\admin\AdminCategoryController;
use App\Http\Controllers\Api\admin\AdminBrandController;
use App\Http\Controllers\Api\admin\AdminBannerController;

/**
 * Admin 
 */

// Category
Route::get('/admin/categories', [AdminCategoryController::class, 'index']);
Route::get('/admin/category/{id}', [AdminCategoryController::class, 'show']);
Route::post('/admin/category', [AdminCategoryController::class, 'store']);
Route::patch('/admin/category/{id}', [AdminCategoryController::class, 'update']);
Route::delete('/admin/category/{id}', [AdminCategoryController::class, 'destroy']);

// Brand
Route::get('/admin/brands', [AdminBrandController::class, 'index']);
Route::get('/admin/brand/{id}', [AdminBrandController::class, 'show']);
Route::post('/admin/brand', [AdminBrandController::class, 'store']);
Route::patch('/admin/brand/{id}', [AdminBrandController::class, 'update']);
Route::delete('/admin/brand/{id}', [AdminBrandController::class, 'destroy']);

// Banner
Route::get('/admin/banners', [AdminBannerController::class, 'index']);
Route::get('/admin/banner/{id}', [AdminBannerController::class, 'show']);
Route::post('/admin/banner', [AdminBannerController::class, 'store']);

