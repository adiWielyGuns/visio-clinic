<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalDokterLog;
use App\Models\Pasien;
use App\Models\PasienRekamMedis;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function index(Request $req)
    {
        if (!isset($req->tanggal_awal)) {
            $req->query->add([
                'tanggal_awal' => Carbon::now()->startOfMonth()->format('d/m/Y'),
            ]);
        }

        if (!isset($req->tanggal_akhir)) {
            $req->query->add([
                'tanggal_akhir' => Carbon::now()->endOfMonth()->format('d/m/Y'),
            ]);
        }

        if (!isset($req->jenis_laporan)) {
            $req->query->add([
                'jenis_laporan' => 'laporan_jumlah_pasien'
            ]);
        }

        if (!isset($req->jenis_laporan)) {
            $req->query->add([
                'jenis_laporan' => 'laporan_jumlah_pasien'
            ]);
        }

        if ($req->jenis_laporan == 'laporan_jumlah_pasien') {
            $data = JadwalDokterLog::where(function ($q) use ($req) {
                $q->where('tanggal', '>=', dateStore($req->tanggal_awal));
                $q->where('tanggal', '<=', dateStore($req->tanggal_akhir));
            })->whereHas('pasien_rekam_medis')->get();
        } else {
            $data = JadwalDokterLog::where('status', 'Reserved')->where(function ($q) use ($req) {
                $q->where('tanggal', '>=', dateStore($req->tanggal_awal));
                $q->where('tanggal', '<=', dateStore($req->tanggal_akhir));
            })->get();
        }



        return view('laporan/laporan', compact('req', 'data'));
    }

    public function datatable(Request $req)
    {
        $data = Pasien::all();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('pasien/action', compact('data'));
            })
            ->addColumn('status', function ($data) {
                if ($data->status == 'true') {
                    return '<button class="btn btn--primary" onclick="gantiStatus(false,\'' . $data->id . '\')">Aktif</button>';
                } else {
                    return '<button class="btn btn--danger" onclick="gantiStatus(true,\'' . $data->id . '\')">Tidak Aktif</button>';
                }
            })
            ->rawColumns(['aksi', 'status'])
            ->addIndexColumn()
            ->make(true);
    }


    public function datatableRekamMedis(Request $req)
    {
        $data = PasienRekamMedis::where('pasien_id', $req->id)->get();;

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('pasien/action', compact('data'));
            })
            ->addColumn('dokter', function ($data) {
                return $data->dokter->name;
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function generatekode(Request $req)
    {
        $kode =  'RM';
        $sub = strlen($kode) + 1;
        $index = Pasien::selectRaw('max(substring(id_pasien,' . $sub . ')) as id')
            ->where('id_pasien', 'like', $kode . '%')
            ->first();

        $collect = Pasien::selectRaw('substring(id_pasien,' . $sub . ') as id')
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

    public function create(Request $req)
    {
        $kode = $this->generatekode($req)->getData()->kode;
        return view('pasien/create_pasien', compact('kode'));
    }

    public function edit(Request $req)
    {
        $data = Pasien::findOrFail($req->id);
        return view('pasien/edit_pasien', compact('data'));
    }

    public function show(Request $req)
    {
        $data = Pasien::findOrFail($req->id);
        $tanggalTerakhirPeriksa = JadwalDokterLog::where('pasien_id', $data->id)
            ->where('status', 'Done')
            ->first();
        $tanggalReservasi = JadwalDokterLog::where('pasien_id', $req->id)
            ->where('status', 'Reserved')
            ->orderBy('tanggal', 'DESC')
            ->first();
        $rm = PasienRekamMedis::where('pasien_id', $req->id)->get();
        return view('pasien/show_pasien', compact('data', 'tanggalReservasi', 'tanggalTerakhirPeriksa', 'rm'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();


            $input['id'] = Pasien::max('id') + 1;
            $input['created_by'] = me();
            $input['updated_by'] = me();
            $input['status'] = 'true';
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);

            Pasien::create($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function update(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();

            $input['updated_by'] = me();
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);

            Pasien::find($req->id)->update($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil diupdate']);
        });
    }

    public function delete(Request $req)
    {
        Pasien::findOrFail($req->id)->delete();
        return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
    }

    public function status(Request $req)
    {
        return DB::transaction(function () use ($req) {
            \App\Models\Pasien::where('id', $req->id)
                ->update([
                    'status' => $req->param
                ]);
            return Response()->json(['status' => 1, 'message' => 'Status berhasil dirubah']);
        });
    }
}
