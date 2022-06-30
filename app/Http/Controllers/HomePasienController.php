<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\JadwalDokterLog;
use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomePasienController extends Controller
{
    public function index()
    {
        $dokter = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Terapis');
        })->get();
        return view('dashboard_pasien', compact('dokter'));
    }

    public function getJadwalDokter(Request $req)
    {
        $data = JadwalDokter::where('users_id', $req->id)
            ->where('status', 'true')
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
            }
        }

        return Response()->json(['status' => 1, 'data' => $data]);
    }

    public function generatekode($tanggal)
    {

        $kode =  'R';
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
            $check = JadwalDokter::where('id', $req->jadwal_dokter_id)
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
            foreach ($dateArray as $key => $value) {
                if (CarbonParse($value, 'l') == convertHariToDay($check->hari)) {
                    $tanggal = $value;
                }
            }

            $pasien = Pasien::find(Auth::guard('pasien')->user()->id);

            JadwalDokterLog::where('tanggal', '>=', Carbon::now()->subDay(-7)->startOfWeek()->format('Y-m-d'))
                ->where('tanggal', '<=', Carbon::now()->subDay(-7)->endOfWeek()->format('Y-m-d'))
                ->where('pasien_id', Auth::guard('pasien')->user()->id)
                ->where('status', 'Reserved')
                ->delete();

            if ($req->param == 'reservasi') {
                $id = JadwalDokterLog::where('jadwal_dokter_id', $req->jadwal_dokter_id)->max('id') + 1;
                JadwalDokterLog::create([
                    'jadwal_dokter_id'  => $req->jadwal_dokter_id,
                    'id'    =>  $id,
                    'pasien_id' => Auth::guard('pasien')->user()->id,
                    'hari'  => $check->hari,
                    'jenis'   => 'On The Spot',
                    'tanggal'   => $tanggal,
                    'no_reservasi'  => $this->generatekode($tanggal)->getData()->kode,
                    'telp'  => $pasien->telp,
                    'alamat'    => $pasien->alamat,
                    'status'    => 'Reserved',
                ]);
            } else {
                $id = JadwalDokterLog::where('jadwal_dokter_id', $req->jadwal_dokter_id)->max('id') + 1;
                JadwalDokterLog::create([
                    'jadwal_dokter_id'  => $req->jadwal_dokter_id,
                    'id'    =>  $id,
                    'pasien_id' => Auth::guard('pasien')->user()->id,
                    'hari'  => $check->hari,
                    'jenis'   => 'Panggilan',
                    'tanggal'   => $tanggal,
                    'no_reservasi'  => $this->generatekode($tanggal)->getData()->kode,
                    'telp'  => $req->telp,
                    'alamat'    => $req->alamat,
                    'status'    => 'Reserved',
                ]);
            }

            return redirect()->route('antrian', ['id' => Crypt::encrypt($id), 'jadwal_dokter_id' => Crypt::encrypt($req->jadwal_dokter_id)]);
        });
    }

    public function antrian(Request $req)
    {
        $id = Crypt::decrypt($req->id);
        $jadwal_dokter_id = Crypt::decrypt($req->jadwal_dokter_id);

        $data = JadwalDokterLog::where('jadwal_dokter_id', $jadwal_dokter_id)->where('id', $id)->first();

        return view('pasien/antrian', compact('data'));
    }
}
