<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDetail extends Model
{
    protected $table = "pembayaran_detail";
    protected $primaryKey = 'pembayaran_id';

    protected $fillable = [
        'pembayaran_id',
        'id',
        'item_id',
        'total',
        'created_at',
        'updated_at'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
