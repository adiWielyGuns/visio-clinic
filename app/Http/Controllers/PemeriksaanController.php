<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokterLog;
use App\Models\PasienRekamMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PemeriksaanController extends Controller
{
    public function index(Request $req)
    {
        $onSite = JadwalDokterLog::where('status', 'Reserved')
            ->whereHas('jadwal_dokter', function ($q) {
                if (Auth::user()->role->name == 'Terapis') {
                    $q->where('users_id', Auth::user()->id);
                }
                $q->where('jenis', 'On Site');
            })
            ->where(function ($q) use ($req) {
                if (isset($req->tanggal_on_site)  && $req->tanggal_on_site != '') {
                    $q->where('tanggal', dateStore($req->tanggal_on_site));
                }
            })
            ->get();

        $panggilan = JadwalDokterLog::where('status', 'Reserved')
            ->whereHas('jadwal_dokter', function ($q) {
                if (Auth::user()->role->name == 'Terapis') {
                    $q->where('users_id', Auth::user()->id);
                }
                $q->where('jenis', 'Panggilan');
            })
            ->where(function ($q) use ($req) {
                if (isset($req->tanggal_panggilan) && $req->tanggal_panggilan != '') {
                    $q->where('tanggal', dateStore($req->tanggal_panggilan));
                }
            })
            ->get();

        return view('pemeriksaan/pemeriksaan', compact('onSite', 'panggilan', 'req'));
    }

    public function datatable(Request $req)
    {
        $data = JadwalDokterLog::all();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('pemeriksaan/action', compact('data'));
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function generatekode(Request $req)
    {
        $kode =  'PN';
        $sub = strlen($kode) + 1;
        $index = PasienRekamMedis::selectRaw('max(substring(id_rekam_medis,' . $sub . ')) as id')
            ->where('id_rekam_medis', 'like', $kode . '%')
            ->first();

        $collect = PasienRekamMedis::selectRaw('substring(id_rekam_medis,' . $sub . ') as id')
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
        return view('pemeriksaan/create_pemeriksaan');
    }

    public function edit(Request $req)
    {
        $data = User::findOrFail($req->id);
        return view('pemeriksaan/edit_pemeriksaan', compact('data'));
    }

    public function show(Request $req)
    {
        $data = User::findOrFail($req->id);
        return view('pemeriksaan/show_pemeriksaan', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();
            $validator = Validator::make(
                $input,
                [
                    'email'       => 'required|unique:users|email',
                    'username'       => 'unique:users',
                ],
                [
                    'email.email'        => 'Format Email Salah',
                    'email.unique'        => 'Email sudah ada',
                    'username.unique'        => 'Username sudah ada',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), Response::HTTP_BAD_REQUEST);
            }

            $input['created_by'] = me();
            $input['updated_by'] = me();
            $input['username'] = $req->user_id;
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);
            $input['password'] = Hash::make(str_replace('/', '', $req->tanggal_lahir));

            User::create($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function update(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();
            $validator = Validator::make(
                $input,
                [
                    'email'       => 'required|unique:users' . ($req->id == 'undefined' ? '' : ",email,$req->id"),
                ],
                [
                    'email.email'        => 'Format Email Salah',
                    'email.unique'        => 'Email sudah ada',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), Response::HTTP_BAD_REQUEST);
            }

            $input['updated_by'] = me();
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);
            $input['password'] = Hash::make(str_replace('/', '', $req->tanggal_lahir));

            User::find($req->id)->update($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function delete(Request $req)
    {
        User::findOrFail($req->id)->delete();
        return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
    }
}
