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
        'users_id',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function jadwal_dokter_log()
    {
        return $this->hasMany(JadwalDokterLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
