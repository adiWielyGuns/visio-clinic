<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modeler;
use App\Models\Pasien;
use App\Models\PasienRekamMedis;
use App\Models\User;
use App\Notifications\AntrianApotek;
use App\Notifications\AntrianKasir;
use App\Notifications\NotifyPembayaran;
use App\Notifications\ObatSelesai;
use App\Notifications\PendaftaranNotification;
use App\Notifications\RawatInap;
use App\Notifications\RequestStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class NotifyController extends Controller
{

    public function authenticate(Request $req)
    {
        $pusher = new Pusher(
            'cfa52e3e9cfd17d0f3ec',
            '088d3c1e37a265d938f5',
            '1431870'
        );

        return $pusher->socket_auth($req->channel_name, $req->socket_id);
    }



    public function notifyPembayaran(Request $req)
    {
        $data = PasienRekamMedis::where('id_rekam_medis', $req->id_rekam_medis)
            ->first();

        $message = 'Terdapat notifikasi pembayaran dari ' . $data->dokter->name . ' dengan no pemeriksaan ' . $data->id_rekam_medis . ' dan nama pasien ' . $data->pasien->name;

        $user = [];
        $user = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Perawat', 'SuperAdmin']);
        })->get();

        foreach ($user as $item) {
            $item->notify(new NotifyPembayaran($item, $message, $data));
        }
        return Response()->json(['status' => 1, 'message' => 'Sukses mengirim notifikasi ke perawat']);
    }
}
