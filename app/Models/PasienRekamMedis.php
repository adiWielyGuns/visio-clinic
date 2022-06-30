<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienRekamMedis extends Model
{
    protected $table = "pasien_rekam_medis";
    protected $primaryKey = 'pasien_id';

    protected $fillable = [
        'pasien_id',
        'id',
        'id_rekam_medis',
        'tanggal',
        'dokter_id',
        'tindakan',
        'keterangan',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id', 'id');
    }
}
