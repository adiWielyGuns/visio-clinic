<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran/pembayaran');
    }

    public function datatable(Request $req)
    {
        $data = JadwalDokter::all();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('jadwal_dokter/action', compact('data'));
            })
            ->addColumn('dokter', function ($data) {
                return $data->dokter->name;
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

    public function edit(Request $req)
    {
        $dokter = User::whereHas('role', function ($q) use ($req) {
            $q->where('name', 'Terapis');
        })->where(function ($q) {
            if (Auth::user()->role == 'Terapis') {
                $q->where('id', Auth::user()->id);
            }
        })->get();
        $data = JadwalDokter::findOrFail($req->id);
        return view('jadwal_dokter/edit_jadwal_dokter', compact('dokter', 'data'));
    }

    public function create(Request $req)
    {
        $dokter = User::whereHas('role', function ($q) use ($req) {
            $q->where('name', 'Terapis');
        })->where(function ($q) {
            if (Auth::user()->role == 'Terapis') {
                $q->where('id', Auth::user()->id);
            }
        })->get();
        return view('jadwal_dokter/create_jadwal_dokter', compact('dokter'));
    }


    public function show(Request $req)
    {
        $data = JadwalDokter::findOrFail($req->id);
        return view('jadwal_dokter/show_jadwal_dokter', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $check = JadwalDokter::where('hari', $req->hari)
                ->where('users_id', $req->users_id)
                ->first();

            if ($check) {
                return Response()->json(['status' => 2, 'message' => 'Data untuk hari ' . $req->hari . ' untuk dokter ini sudah ada.']);
            }
            $input = $req->all();
            $input['id'] = JadwalDokter::max('id') + 1;
            $input['status'] = 'true';
            $input['created_by'] = me();
            $input['updated_by'] = me();

            JadwalDokter::create($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function update(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $check = JadwalDokter::where('hari', $req->hari)
                ->where('users_id', $req->users_id)
                ->where('id', '!=', $req->id)
                ->first();

            if ($check) {
                return Response()->json(['status' => 2, 'message' => 'Data untuk hari ' . $req->hari . ' untuk dokter ini sudah ada.']);
            }
            $input = $req->all();

            $input['updated_by'] = me();
            JadwalDokter::find($req->id)->update($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil diupdate']);
        });
    }


    public function status(Request $req)
    {
        return DB::transaction(function () use ($req) {
            \App\Models\JadwalDokter::where('id', $req->id)
                ->update([
                    'status' => $req->param
                ]);
            return Response()->json(['status' => 1, 'message' => 'Status berhasil dirubah']);
        });
    }

    public function delete(Request $req)
    {
        JadwalDokter::findOrFail($req->id)->delete();
        return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
    }
}
