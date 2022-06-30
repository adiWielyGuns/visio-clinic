<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $table = "jadwal_dokter";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'hari',
        'kuota',
        'jenis',
        'users_id',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public static $enumJenis = [
        'On Site',
        'Panggilan',
    ];


    public function jadwal_dokter_log()
    {
        return $this->hasMany(JadwalDokterLog::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
