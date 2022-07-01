<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = "item";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'jenis',
        'harga',
        'keterangan',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
