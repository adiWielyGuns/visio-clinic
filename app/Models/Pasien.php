<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = "pasien";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_pasien',
        'name',
        'tanggal_lahir',
        'jenis_kelamin',
        'telp',
        'alamat',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $cast = [
        'tanggal_lahir' => 'date:d/m/Y',
    ];

    public static $enumJenisKelamin = [
        'Laki',
        'Perempuan',
    ];

    public function jadwal_dokter_log()
    {
        return $this->hasMany(JadwalDokterLog::class);
    }
}
