<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokterLog extends Model
{
    protected $table = "jadwal_dokter_log";
    protected $primaryKey = 'jadwal_dokter_id';

    protected $fillable = [
        'jadwal_dokter_id',
        'id',
        'pasien_id',
        'hari',
        'tanggal',
        'no_reservasi',
        'jenis',
        'telp',
        'ref',
        'alamat',
        'status',
        'status_pembayaran',
        'created_at',
        'updated_at'
    ];

    public function jadwal_dokter()
    {
        return $this->belongsTo(JadwalDokter::class);
    }

    public function pasien_rekam_medis()
    {
        return $this->hasOne(PasienRekamMedis::class, 'id_rekam_medis', 'ref');
    }
}
