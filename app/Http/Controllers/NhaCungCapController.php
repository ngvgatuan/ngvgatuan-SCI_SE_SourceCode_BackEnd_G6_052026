<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemMoiNCCRequest;
use App\Mail\KichHoatTaiKhoan;
use App\Mail\KichHoatTaiKhoanNCC;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NhaCungCapController extends Controller
{
    public function dangNhap(Request $request)
    {
        $check = Auth::guard('nha_cung_cap')->attempt([
            'email'    => $request->email,
            'password' => $request->password
        ]);
        if ($check) {
            $user = Auth::guard('nha_cung_cap')->user();
            if ($user->is_active == 0) {
                return response()->json([
                    'message'  =>   'Tài khoản của bạn chưa được kích hoạt!',
                    'status'   =>   2
                ]);
            } else {
                if ($user->is_blocked) {
                    return response()->json([
                        'message'  =>   'Tài khoản của bạn đã bị khóa!',
                        'status'   =>   0
                    ]);
                }

                return response()->json([
                    'message'   =>   'Đã đăng nhập thành công!',
                    'status'    =>   1,
                    'chia_khoa' =>   $user->createToken('ma_so_bi_mat_ncc')->plainTextToken,
                    'ten_kh'    =>   $user->ten_nha_cung_cap
                ]);
            }
        } else {
            return response()->json([
                'message'  =>   'Tài khoản hoặc mật khẩu không đúng!',
                'status'   =>   0
            ]);
        }
    }
    public function kichHoat($hash_active)
    {
        $NhaCungCap = NhaCungCap::where('hash_active', $hash_active)->where('is_active', 0)->first();
        if ($NhaCungCap) {
            $NhaCungCap->is_active = 1;
            $NhaCungCap->hash_active = null;
            $NhaCungCap->save();
            return response()->json([
                'status' => true,
                'message' => "Bạn đã kích hoạt tài khoản thành công!"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Tài khoản bạn đã được kích hoạt hoặc không tồn tại!"
            ]);
        }
    }
    public function create(ThemMoiNCCRequest $request)
    {
        $ncc = NhaCungCap::create([
            'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
            'so_dien_thoai'  => $request->so_dien_thoai,
            'email'          => $request->email,
            'password'       => bcrypt($request->password),
            'hash_active'    => Str::uuid(),
        ]);
        Mail::to($ncc->email)->send(new KichHoatTaiKhoanNCC($ncc->hash_active, $ncc->ten_nha_cung_cap));

        return response()->json([
            'message' => 'Tạo tài khoản thành công!',
            'status'  => true
        ]);
    }
}
