<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\JadwalDokterLog;
use App\Models\Pasien;
use App\Models\PasienRekamMedis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomePasienController extends Controller
{
    public function index()
    {
        $dokter = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Terapis');
        })->get();

        $antrian = JadwalDokterLog::where('tanggal', '>=', Carbon::now()->subDay(-7)->startOfWeek()->format('Y-m-d'))
            ->where('tanggal', '<=', Carbon::now()->subDay(-7)->endOfWeek()->format('Y-m-d'))
            ->where('pasien_id', Auth::guard('pasien')->user()->id)
            ->where('status', 'Reserved')
            ->get();

        foreach ($antrian as $key => $value) {
            $check = JadwalDokterLog::where('jadwal_dokter_id', $value->jadwal_dokter_id)
                ->where('status', 'Reserved')
                ->orderBy('created_at', 'ASC')
                ->first();
            $value->antrian_saat_ini = $check->no_reservasi;
        }

        $rm = PasienRekamMedis::where('pasien_id', Auth::guard('pasien')->user()->id)->get();
        return view('dashboard_pasien', compact('dokter', 'antrian', 'rm'));
    }

    public function getJadwalDokter(Request $req)
    {
        $data = JadwalDokter::where('users_id', $req->id)
            ->where('status', 'true')
            ->where('jenis', $req->param)
            ->get();
        foreach ($data as $i => $value) {

            $kuota = JadwalDokterLog::where('jadwal_dokter_id', $value->id)
                ->where('tanggal', '>=', Carbon::now()->subDay(-7)->startOfWeek()->format('Y-m-d'))
                ->where('tanggal', '<=', Carbon::now()->subDay(-7)->endOfWeek()->format('Y-m-d'))
                ->where('hari', $value->hari)
                ->count();

            if ($value->kuota <= $kuota) {
                unset($value);
            } else {
                $value->sisa_kuota = $value->kuota - $kuota;

                $weekStart = Carbon::now()->subDay(-7)->startOfWeek()->format('Y-m-d');
                $weekEnd = Carbon::now()->subDay(-7)->endOfWeek()->format('Y-m-d');
                $jumlahHari = (strtotime($weekEnd) - strtotime($weekStart)) / 86400;
                $dateArray = [];

                for ($i1 = 0; $i1 < $jumlahHari + 1; $i1++) {
                    $day = Carbon::parse($weekStart)->subDay(-$i1)->format('d');
                    $month = Carbon::parse($weekStart)->subDay(-$i1)->format('m');
                    $year = Carbon::parse($weekStart)->subDay(-$i1)->format('Y');
                    $date = $year . '-' . $month  . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    array_push($dateArray, $date);
                }
                foreach ($dateArray as $d) {
                    if (CarbonParse($d, 'l') == convertHariToDay($value->hari)) {
                        $value->tanggal = CarbonParse($d, 'd/m/Y');
                    }
                }
            }
        }

        return Response()->json(['status' => 1, 'data' => $data]);
    }

    public function generatekode($tanggal, $param)
    {

        $kode =  $param;
        $sub = strlen($kode) + 1;
        $index = JadwalDokterLog::selectRaw('max(substring(no_reservasi,' . $sub . ')) as id')
            ->where('no_reservasi', 'like', $kode . '%')
            ->where('tanggal', $tanggal)
            ->first();

        $collect = JadwalDokterLog::selectRaw('substring(no_reservasi,' . $sub . ') as id')
            ->get();

        $count = (int)$index->id;
        $collect_id = [];
        for ($i = 0; $i < count($collect); $i++) {
            array_push($collect_id, (int)$collect[$i]->id);
        }

        $flag = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($flag == 0) {
                if (!in_array($i + 1, $collect_id)) {
                    $index = $i + 1;
                    $flag = 1;
                }
            }
        }

        if ($flag == 0) {
            $index = (int)$index->id + 1;
        }


        $index = str_pad($index, 4, '0', STR_PAD_LEFT);

        $kode = $kode . $index;

        return Response()->json(['status' => 1, 'kode' => $kode]);
    }

    public function store(Request $req)
    {
        return DB::transaction(function ($q) use ($req) {
            $check =    JadwalDokterLog::where('tanggal', '>=', Carbon::now()->subDay(-7)->startOfWeek()->format('Y-m-d'))
                ->where('tanggal', '<=', Carbon::now()->subDay(-7)->endOfWeek()->format('Y-m-d'))
                ->where('pasien_id', Auth::guard('pasien')->user()->id)
                ->where('status', 'Reserved')
                ->where('jenis', $req->param)
                ->first();

            if ($check) {
                return back()->withErrors([
                    'already' => 'Anda sudah melakukan reservasi, silahkan batalkan dahulu reservasi anda di TAB Antrian.'
                ])->withInput();
            }

            foreach ($req->jadwal_dokter_id as $d) {
                $check = JadwalDokter::where('id', $d)
                    ->first();
                $weekStart = Carbon::now()->subDay(-7)->startOfWeek()->format('Y-m-d');
                $weekEnd = Carbon::now()->subDay(-7)->endOfWeek()->format('Y-m-d');
                $jumlahHari = (strtotime($weekEnd) - strtotime($weekStart)) / 86400;
                $dateArray = [];

                for ($i1 = 0; $i1 < $jumlahHari + 1; $i1++) {
                    $day = Carbon::parse($weekStart)->subDay(-$i1)->format('d');
                    $month = Carbon::parse($weekStart)->subDay(-$i1)->format('m');
                    $year = Carbon::parse($weekStart)->subDay(-$i1)->format('Y');
                    $date = $year . '-' . $month  . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    array_push($dateArray, $date);
                }
                $tanggal = dateStore();
                foreach ($dateArray as $value) {
                    if (CarbonParse($value, 'l') == convertHariToDay($check->hari)) {
                        $tanggal = $value;
                    }
                }

                $pasien = Pasien::find(Auth::guard('pasien')->user()->id);

                if ($req->param == 'On Site') {
                    $id = JadwalDokterLog::where('jadwal_dokter_id', $d)->max('id') + 1;
                    JadwalDokterLog::create([
                        'jadwal_dokter_id'  => $d,
                        'id'    =>  $id,
                        'pasien_id' => Auth::guard('pasien')->user()->id,
                        'hari'  => $check->hari,
                        'jenis'   => 'On Site',
                        'tanggal'   => $tanggal,
                        'no_reservasi'  => $this->generatekode($tanggal, 'R')->getData()->kode,
                        'telp'  => $pasien->telp,
                        'alamat'    => $pasien->alamat,
                        'status'    => 'Reserved',
                    ]);
                } else {
                    $id = JadwalDokterLog::where('jadwal_dokter_id', $d)->max('id') + 1;
                    JadwalDokterLog::create([
                        'jadwal_dokter_id'  => $d,
                        'id'    =>  $id,
                        'pasien_id' => Auth::guard('pasien')->user()->id,
                        'hari'  => $check->hari,
                        'jenis'   => 'Panggilan',
                        'tanggal'   => $tanggal,
                        'no_reservasi'  => $this->generatekode($tanggal, 'PR')->getData()->kode,
                        'telp'  => $req->telp,
                        'alamat'    => $req->alamat,
                        'status'    => 'Reserved',
                    ]);
                }
            }

            return redirect()->route('antrian', ['id' => Crypt::encrypt(Auth::guard('pasien')->user()->id), 'jadwal_dokter_id' => $req->jadwal_dokter_id]);
        });
    }

    public function delete(Request $req)
    {
        JadwalDokterLog::where('jadwal_dokter_id', $req->jadwal_dokter_id)->where('id', $req->id)->delete();
        return Response()->json(['status' => 1, 'message' => 'Reservasi berhasil dibatalkan']);
    }

    public function antrian(Request $req)
    {
        $id = Crypt::decrypt($req->id);

        $data = JadwalDokterLog::whereIn('jadwal_dokter_id', $req->jadwal_dokter_id)->where('pasien_id', $id)->where('status', 'Reserved')->get();
        return view('pasien/antrian', compact('data'));
    }

    public function resetPassword()
    {
        User::update([
            'password' => Hash::make('12345678')
        ]);

        return Response()->json(['status' => 1, 'message' => 'Berhasil reset password menjadi 12345678 untuk semua user']);
    }
}
