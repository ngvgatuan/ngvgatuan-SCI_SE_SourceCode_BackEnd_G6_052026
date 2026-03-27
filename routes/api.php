<?php

use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhaCungCapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//khách hàng
Route::post('/khach-hang/dang-ky', [KhachHangController::class, 'create']);
//mail kích hoạt
Route::get('/khach_hang/kich-hoat-tai-khoan/{hash_active}', [KhachHangController::class, 'kichHoat']);

//Nhà cung cấp
Route::post('/nha-cung-cap/dang-ky', [NhaCungCapController::class, 'create']);
//mail kích hoạt
Route::get('/nha-cung-cap/kich-hoat-tai-khoan/{hash_active}', [NhaCungCapController::class, 'kichHoat']);
