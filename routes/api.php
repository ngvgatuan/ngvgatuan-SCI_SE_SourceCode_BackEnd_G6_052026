<?php

use App\Http\Controllers\KhachHangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//khách hàng
Route::post('/khach-hang/dang-ky',[KhachHangController::class, 'create']);
//mail kích hoạt
Route::get('/kich-hoat-tai-khoan/{hash_active}', [KhachHangController::class, 'kichHoat']);