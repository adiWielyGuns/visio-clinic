<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\PasienRekamMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $pasien = PasienRekamMedis::where('tanggal', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'))
            ->where('tanggal', '<=', Carbon::now()->endOfMonth()->format('Y-m-d'))
            ->count();


        $terapi = PasienRekamMedis::where('tanggal', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'))
            ->where('tanggal', '<=', Carbon::now()->endOfMonth()->format('Y-m-d'))
            ->whereHas('jadwal_dokter_log', function ($q) {
                $q->where('jenis', 'On Site');
            })
            ->count();

        $kunjungan = PasienRekamMedis::where('tanggal', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'))
            ->where('tanggal', '<=', Carbon::now()->endOfMonth()->format('Y-m-d'))
            ->whereHas('jadwal_dokter_log', function ($q) {
                $q->where('jenis', 'Panggilan');
            })
            ->count();

        $pasienHariIni = PasienRekamMedis::where('tanggal', '>=', dateStore())
            ->where('tanggal', '<=', dateStore())
            ->count();

        $pasienMingguIni = PasienRekamMedis::where('tanggal', '>=', Carbon::now()->startOfWeek()->format('Y-m-d'))
            ->where('tanggal', '<=', Carbon::now()->endOfWeek()->format('Y-m-d'))
            ->count();

        return view('dashboard', compact('pasien', 'terapi', 'kunjungan', 'pasienHariIni', 'pasienMingguIni'));
    }
}
