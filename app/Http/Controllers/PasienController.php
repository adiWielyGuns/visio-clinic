<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokterLog;
use App\Models\Pasien;
use App\Models\PasienRekamMedis;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PasienController extends Controller
{
    public function index()
    {
        return view('pasien/pasien');
    }

    public function datatable(Request $req)
    {
        $data = Pasien::all();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('pasien/action', compact('data'));
            })
            ->rawColumns(['aksi'])
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
        return view('pasien/show_pasien', compact('data', 'tanggalReservasi', 'tanggalTerakhirPeriksa'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();


            $input['id'] = Pasien::max('id') + 1;
            $input['created_by'] = me();
            $input['updated_by'] = me();
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
}
