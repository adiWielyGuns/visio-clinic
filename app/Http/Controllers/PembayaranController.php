<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PasienRekamMedis;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran/pembayaran');
    }

    public function datatable(Request $req)
    {
        $data = Pembayaran::where(function ($q) use ($req) {
            if ($req->metode_pembayaran != '') {
                $q->where('metode_pembayaran', $req->metode_pembayaran);
            }

            if ($req->tanggal_awal != '') {
                $q->where('tanggal', '>=', dateStore($req->tanggal_awal));
            }

            if ($req->tanggal_akhir != '') {
                $q->where('tanggal', '<=', dateStore($req->tanggal_akhir));
            }
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
            ->addColumn('total', function ($data) {
                return number_format($data->total);
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
        $item = Item::where('status', 'true')->get();
        $rekamMedis = PasienRekamMedis::where('status_pembayaran', 'Released')->get();
        $data = Pembayaran::findOrFail($req->id);

        return view('pembayaran/edit_pembayaran', compact('data', 'item', 'rekamMedis'));
    }

    public function itemGenerate(Request $req)
    {
        $item = Item::whereIn('id', explode(',', $req->item))
            ->get();
        return Response()->json(['status' => 1, 'message' => 'Status berhasil dirubah', 'item' => $item]);
    }

    public function create(Request $req)
    {
        if (isset($req->notification_id)) {
            Auth::user()->unreadNotifications->where('id', $req->notification_id)->markAsRead();
        }
        $item = Item::where('status', 'true')->get();
        $rekamMedis = PasienRekamMedis::where('status_pembayaran', null)->get();
        return view('pembayaran/create_pembayaran', compact('item', 'rekamMedis'));
    }


    public function show(Request $req)
    {
        if (isset($req->notification_id)) {
            Auth::user()->unreadNotifications->where('id', $req->notification_id)->markAsRead();
        }

        $data = Pembayaran::findOrFail($req->id);
        return view('pembayaran/show_pembayaran', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {


            $rekamMedis = PasienRekamMedis::where('id', $req->rekam_medis_id)
                ->where('pasien_id', $req->pasien_id)
                ->first();

            if (!$rekamMedis) {
                return Response()->json(['status' => 2, 'message' => 'Pemeriksaan pasien ini tidak ada.']);
            }

            $idPembayaran = Pembayaran::max('id') + 1;
            Pembayaran::create([
                'id'    => $idPembayaran,
                'nomor_invoice' => $this->generatekode($req)->getData()->kode,
                'ref' => $rekamMedis->id_rekam_medis,
                'tanggal'   => dateStore($req->tanggal),
                'pasien_id' => $req->pasien_id,
                'metode_pembayaran' => $req->metode_pembayaran,
                'total' => convertNumber($req->total),
                'bank'  => $req->bank,
                'no_rekening'   => $req->no_rekening,
                'no_transaksi'  => $req->no_transaksi,
                'status'    => $req->metode_pembayaran == 'Tunai' ? 'Done' : 'Released',
                'created_by'    => me(),
                'updated_by'    => me(),
            ]);

            PembayaranDetail::where('pembayaran_id', $idPembayaran)->delete();

            foreach ($req->item as $key => $value) {
                $item = Item::find($value);
                PembayaranDetail::create([
                    'pembayaran_id' => $idPembayaran,
                    'id'    => $key + 1,
                    'item_id'   => $value,
                    'qty'   => 1,
                    'total' => $item->harga,
                ]);
            }

            $rekamMedis = PasienRekamMedis::where('id', $req->rekam_medis_id)
                ->where('pasien_id', $req->pasien_id)
                ->update([
                    'status_pembayaran' => 'Done'
                ]);

            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan', 'id' => $idPembayaran]);
        });
    }

    public function update(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $idPembayaran = $req->id;
            Pembayaran::find($idPembayaran)->update([
                'metode_pembayaran' => $req->metode_pembayaran,
                'total' => convertNumber($req->total),
                'bank'  => $req->bank,
                'no_rekening'   => $req->no_rekening,
                'no_transaksi'  => $req->no_transaksi,
                'status'    => $req->metode_pembayaran == 'Tunai' ? 'Done' : 'Released',
                'updated_by'    => me(),
            ]);

            PembayaranDetail::where('pembayaran_id', $idPembayaran)->delete();
            foreach ($req->item as $key => $value) {
                $item = Item::find($value);
                PembayaranDetail::create([
                    'pembayaran_id' => $idPembayaran,
                    'id'    => $key + 1,
                    'item_id'   => $value,
                    'qty'   => 1,
                    'total' => $item->harga,
                ]);
            }

            return Response()->json(['status' => 1, 'message' => 'Data berhasil diupdate', 'id' => $idPembayaran]);
        });
    }


    public function status(Request $req)
    {
        return DB::transaction(function () use ($req) {
            \App\Models\Pembayaran::where('id', $req->id)
                ->update([
                    'status' => $req->param
                ]);
            return Response()->json(['status' => 1, 'message' => 'Status berhasil dirubah']);
        });
    }

    public function laporan(Request $req)
    {

        if ($req->tanggal_awal == '' or !isset($req->tanggal_awal)) {
            $tanggal_awal = carbon::now()->startOfMonth()->format('Y-m-d');
        } else {
            $tanggal_awal = $req->tanggal_awal;
        }

        if ($req->tanggal_akhir == '' or !isset($req->tanggal_akhir)) {
            $tanggal_akhir = carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $tanggal_akhir = $req->tanggal_akhir;
        }

        $data = Pembayaran::where(function ($q) use ($req, $tanggal_awal, $tanggal_akhir) {
            if ($req->metode_pembayaran != '') {
                $q->where('metode_pembayaran', $req->metode_pembayaran);
            }

            if ($tanggal_awal != '') {
                $q->where('tanggal', '>=', dateStore($tanggal_awal));
            }

            if ($tanggal_akhir != '') {
                $q->where('tanggal', '<=', dateStore($tanggal_akhir));
            }
        })->get();

        return view('pembayaran/laporan_pembayaran', compact('data', 'tanggal_akhir', 'tanggal_awal'));
    }

    public function delete(Request $req)
    {
        return DB::transaction(function () use ($req) {
            $data = Pembayaran::findOrFail($req->id);

            PasienRekamMedis::where('id_rekam_medis', $data->ref)
                ->update(['status_pembayaran' => null]);

            Pembayaran::findOrFail($req->id)->delete();
            PembayaranDetail::where('pembayaran_id', $req->id)->delete();

            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function print(Request $req)
    {
        $data = Pembayaran::find($req->id);
        $nama = 'E-INVOICE ' . $data->kode . '-' . Carbon::parse($data->tanggal)->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('pembayaran/print_pembayaran', compact('data'))
            ->setPaper('a4', 'potrait');
        return $pdf->stream($nama);
    }
}
