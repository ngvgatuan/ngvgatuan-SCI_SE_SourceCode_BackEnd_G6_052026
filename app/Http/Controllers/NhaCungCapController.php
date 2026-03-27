<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemMoiNCCRequest;
use App\Mail\KichHoatTaiKhoan;
use App\Mail\KichHoatTaiKhoanNCC;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NhaCungCapController extends Controller
{
    public function kichHoat($hash_active)
    {
        $NhaCungCap = NhaCungCap::where('hash_active', $hash_active)->first();
        if (!$NhaCungCap) {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản không tồn tại'
            ]);
        } else if ($NhaCungCap->is_active == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản đã được kích hoạt trước đó'
            ]);
        } else {
            $NhaCungCap->is_active = 1;
            $NhaCungCap->hash_active = null;
            $NhaCungCap->save();
            return redirect('http://localhost:3000/'); //route tới trang xác nhận thành công nhé
            //     return response()->json([
            //         'status' => true,
            //         'message' => 'Kích hoạt tài khoản thành công!'
            //     ]);
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
