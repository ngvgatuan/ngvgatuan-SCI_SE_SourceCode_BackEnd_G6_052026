<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemMoiKhachHangRequest;
use App\Mail\KichHoatTaiKhoan;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class KhachHangController extends Controller
{
    public function kichHoat($hash_active)
    {
        $KhachHang = KhachHang::where('hash_active', $hash_active)->first();
        if (!$KhachHang) {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản không tồn tại'
            ]);
        } else if ($KhachHang->is_active == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản đã được kích hoạt trước đó'
            ]);
        } else {
            $KhachHang->is_active = 1;
            $KhachHang->hash_active = null;
            $KhachHang->save();
            return redirect('http://localhost:3000/'); //route tới trang xác nhận thành công nhé
            //     return response()->json([
            //         'status' => true,
            //         'message' => 'Kích hoạt tài khoản thành công!'
            //     ]);
        }
    }
    public function create(ThemMoiKhachHangRequest $request)
    {
        $KhachHang = KhachHang::create([
            'ten_khach_hang' => $request->ten_khach_hang,
            'so_dien_thoai'  => $request->so_dien_thoai,
            'email'          => $request->email,
            'password'       => bcrypt($request->password),
            'hash_active'    => Str::uuid(),
        ]);
        Mail::to($KhachHang->email)->send(new KichHoatTaiKhoan($KhachHang->hash_active, $KhachHang->ten_khach_hang));

        return response()->json([
            'message' => 'Tạo tài khoản thành công!',
            'status'  => true
        ]);
    }
}
