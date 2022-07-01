<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = "pembayaran";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nomor_invoice',
        'tanggal',
        'pasien_id',
        'metode_pembayaran',
        'total',
        'diagnosa',
        'keterangan',
        'bank',
        'no_rekening',
        'no_transaksi',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];


    public function PembayaranDetail()
    {
        return $this->hasMany(PembayaranDetail::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'id');
    }
}
