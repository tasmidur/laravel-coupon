<?php

use Illuminate\Support\Facades\Route;

Route::get('/coupons', [\Tasmidur\Coupon\Http\Controllers\CouponController::class, 'index'])->name('coupons.index');
Route::post('/coupons', [\Tasmidur\Coupon\Http\Controllers\CouponController::class, 'store'])->name('coupons.store');
Route::get('/get-coupon-list', [\Tasmidur\Coupon\Http\Controllers\CouponController::class, 'getList'])->name('coupons.getList');
