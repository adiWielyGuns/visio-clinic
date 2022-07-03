<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginPasienController extends Controller
{
    public function create()
    {

        if (Auth::guard('pasien')->check()) {
            return redirect()->intended(RouteServiceProvider::HOME_PASIEN);
        }

        return view('layouts/home-pasien');
    }

    public function store(Request $req)
    {
        $check = Pasien::where('id_pasien', $req->id_pasien)
            ->where('tanggal_lahir', $req->tanggal_lahir)
            ->where('status', 'true')
            ->first();
        if ($check) {
            Auth::guard('pasien')->attempt(['id_pasien' => $req->id_pasien, 'password' => $req->tanggal_lahir]);
            return redirect()->route('dashboard-pasien');
        } else {
            return back()->withErrors([
                'credential' => 'Tidak ada data pasien terdaftar'
            ]);
        }
    }

    public function destroy(Request $req)
    {
        Auth::guard('pasien')->logout();

        $req->session()->invalidate();

        $req->session()->regenerateToken();

        return redirect('/');
    }
}
