<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PasienRekamMedis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran/pembayaran');
    }

    public function datatable(Request $req)
    {
        $data = Pembayaran::where(function ($q) use ($req) {
        })->get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('pembayaran/action', compact('data'));
            })
            ->addColumn('pasien', function ($data) {
                return $data->pasien->name;
            })
            ->addColumn('status', function ($data) {
                return $data->status;
            })
            ->rawColumns(['aksi', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function generatekode(Request $req)
    {
        $tanggal = dateStore($req->tanggal);
        $kode =  'INV/' . CarbonParse($tanggal, 'm') . CarbonParse($tanggal, 'Y') . '/';
        $sub = strlen($kode) + 1;
        $index = Pembayaran::selectRaw('max(substring(nomor_invoice,' . $sub . ')) as id')
            ->where('nomor_invoice', 'like', $kode . '%')
            ->first();

        $collect = Pembayaran::selectRaw('substring(nomor_invoice,' . $sub . ') as id')
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

    public function edit(Request $req)
    {

        $data = Pembayaran::findOrFail($req->id);
        return view('pembayaran/edit_pembayaran', compact('dokter'));
    }

    public function itemGenerate(Request $req)
    {
        $item = Item::whereIn('id', explode(',', $req->item))
            ->get();
        return Response()->json(['status' => 1, 'message' => 'Status berhasil dirubah', 'item' => $item]);
    }

    public function create(Request $req)
    {
        $item = Item::where('status', 'true')->get();
        $rekamMedis = PasienRekamMedis::where('status_pembayaran', 'Released')->get();
        return view('pembayaran/create_pembayaran', compact('item', 'rekamMedis'));
    }


    public function show(Request $req)
    {
        $data = JadwalDokter::findOrFail($req->id);
        return view('pembayaran/show_pembayaran', compact('data'));
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
