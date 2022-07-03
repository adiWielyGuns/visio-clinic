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
    public $notify;
    public function __construct()
    {
        $this->notify = new NotifyController();
    }

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

        $history = JadwalDokterLog::where('status', 'Done')
            ->whereHas('jadwal_dokter', function ($q) {
                if (Auth::user()->role->name == 'Terapis') {
                    $q->where('users_id', Auth::user()->id);
                }
            })
            ->where(function ($q) use ($req) {
                if (isset($req->tanggal_history) && $req->tanggal_history != '') {
                    $q->where('tanggal', dateStore($req->tanggal_history));
                }
            })
            ->get();


        return view('pemeriksaan/pemeriksaan', compact('onSite', 'panggilan', 'req', 'history'));
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
        $kode =  'PE';
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
        $data = JadwalDokterLog::where('status', 'Reserved')
            ->where('id', $req->id)
            ->where('jadwal_dokter_id', $req->jadwal_dokter_id)
            ->first();
        return view('pemeriksaan/create_pemeriksaan', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();
            $check = JadwalDokterLog::where('status', 'Reserved')
                ->where('id', $req->id)
                ->where('jadwal_dokter_id', $req->jadwal_dokter_id)
                ->first();
            $id_rekam_medis = $this->generatekode($req)->getData()->kode;
            JadwalDokterLog::where('status', 'Reserved')
                ->where('id', $req->id)
                ->where('jadwal_dokter_id', $req->jadwal_dokter_id)
                ->update([
                    'status' => 'Done',
                    'ref'   => $id_rekam_medis,
                ]);
            $input['created_by'] = me();
            $input['updated_by'] = me();
            $input['tanggal'] = dateStore();
            $input['dokter_id'] = $check->jadwal_dokter->users_id;
            $input['pasien_id'] = $check->pasien_id;
            $input['id_rekam_medis'] =  $id_rekam_medis;
            $input['id'] = PasienRekamMedis::where('pasien_id', $check->pasien_id)->max('id') + 1;
            $req->request->add([
                'id_rekam_medis' => $id_rekam_medis
            ]);
            PasienRekamMedis::create($input);

            $this->notify->notifyPembayaran($req);

            return Response()->json(['status' => 1, 'message' => 'Berhasil melakukan pemeriksaan']);
        });
    }
}
