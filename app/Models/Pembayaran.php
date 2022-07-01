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
        'ref',
        'tanggal',
        'pasien_id',
        'metode_pembayaran',
        'total',
        'bank',
        'no_rekening',
        'no_transaksi',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public static $enumMetodePembayaran = [
        'Tunai',
        'Non Tunai',
    ];

    public static $enumBank = [
        'Bank Transfer (BCA)',
        'Bank Transfer (Mandiri)',
        'Bank Transfer (BRI)',
    ];

    public function pembayaran_detail()
    {
        return $this->hasMany(PembayaranDetail::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'id');
    }

    public function pasien_rekam_medis()
    {
        return $this->hasOne(PasienRekamMedis::class, 'id_rekam_medis', 'ref');
    }
}
