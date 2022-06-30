<?php

use App\Models\KamarRawatInapDanBedahDetail;
use App\Models\Owner;
use App\Models\RekamMedisPasien;
use App\Models\RekamMedisRekomendasiTindakanBedah;
use Illuminate\Support\Facades\Auth;

function dateStore($param = null)
{
    if ($param != null) {
        return \carbon\carbon::parse(str_replace('/', '-', $param))->format('Y-m-d');
    } else {
        return \carbon\carbon::now()->format('Y-m-d');
    }
}

function dateShow($date = null)
{
    if ($date != null) {
        return \carbon\carbon::parse($date)->format('d F Y');
    } else {
        return \carbon\carbon::now()->format('d F Y');
    }
}

function pasienActive()
{
    $kamar = KamarRawatInapDanBedahDetail::where('status', 'In Use')
        ->whereHas('KamarRawatInapDanBedah', function ($q) {
            if (!Auth::user()->akses('global', null, false)) {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })
        ->count();
    return $kamar;
}

function bedahActive()
{
    $data = RekamMedisRekomendasiTindakanBedah::whereHas('RekamMedisPasien', function ($q) {
        $q->whereHas('Pendaftaran', function ($q) {
            if (!Auth::user()->akses('global', null, false)) {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        });
    })->where('status', 'Released')->count();

    return $data;
}

function apotekActive()
{
    $pasien = RekamMedisPasien::whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa'])
        ->where(function ($q) {
            if (!Auth::user()->akses('global', null, false)) {
                $q->whereHas('Pendaftaran', function ($q) {
                    $q->where('branch_id', Auth::user()->branch_id);
                });
            }
        })
        ->where('status_pengambilan_obat', false)
        ->where('status_pembayaran', false)
        ->whereHas('pendaftaran', function ($q) {
            $q->where('status', 'Completed');
        })
        ->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap'])
        ->count();

    $pasienRawatInap = RekamMedisPasien::where('status_pemeriksaan', 'Rawat Inap')
        ->where(function ($q) {
            if (!Auth::user()->akses('global', null, false)) {
                $q->whereHas('Pendaftaran', function ($q) {
                    $q->where('branch_id', Auth::user()->branch_id);
                });
            }
        })
        ->whereHas('rekamMedisResep', function ($q) {
            $q->where('status_pembuatan_obat', 'Undone');
            $q->where('status_resep', 'Langsung');
        })
        ->where('status_pengambilan_obat', false)
        ->where('status_pembayaran', false)
        ->whereHas('pendaftaran', function ($q) {
            $q->where('status', 'Completed');
        })
        ->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap'])
        ->count();

    return $pasienRawatInap + $pasien;
}

function pembayaranActive()
{
    $pasien = Owner::whereHas('pasien', function ($q) {
        $q->whereHas('rekamMedisPasien', function ($q) {
            $q->whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa']);
            $q->where(function ($q) {
                if (!Auth::user()->akses('global', null, false)) {
                    $q->whereHas('Pendaftaran', function ($q) {
                        $q->where('branch_id', Auth::user()->branch_id);
                    });
                }
            });
            $q->where('status_pembayaran', false);
            $q->where('rawat_inap', false);
            $q->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'Completed');
            });
            $q->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap']);
        });
    })->with(['pasien' => function ($q) {
        $q->whereHas('rekamMedisPasien', function ($q) {
            $q->whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa']);
            $q->where(function ($q) {
                if (!Auth::user()->akses('global', null, false)) {
                    $q->whereHas('Pendaftaran', function ($q) {
                        $q->where('branch_id', Auth::user()->branch_id);
                    });
                }
            });
            $q->where('status_pembayaran', false);
            $q->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'Completed');
            });
            $q->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap']);
        });
        $q->with(['rekamMedisPasien' => function ($q) {
            $q->whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa']);
            $q->where(function ($q) {
                if (!Auth::user()->akses('global', null, false)) {
                    $q->whereHas('Pendaftaran', function ($q) {
                        $q->where('branch_id', Auth::user()->branch_id);
                    });
                }
            });
            $q->where('status_pembayaran', false);
            $q->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'Completed');
            });
            $q->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap']);
        }]);
    }])->count();

    $pasienRawatInap = Owner::whereHas('pasien', function ($q) {
        $q->whereHas('rekamMedisPasien', function ($q) {
            $q->whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa']);
            $q->where(function ($q) {
                if (!Auth::user()->akses('global', null, false)) {
                    $q->whereHas('Pendaftaran', function ($q) {
                        $q->where('branch_id', Auth::user()->branch_id);
                    });
                }
            });
            $q->where('status_pembayaran', false);
            $q->where('rawat_inap', true);
            $q->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'Completed');
            });
            $q->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap']);
        });
    })->with(['pasien' => function ($q) {
        $q->whereHas('rekamMedisPasien', function ($q) {
            $q->whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa']);
            $q->where(function ($q) {
                if (!Auth::user()->akses('global', null, false)) {
                    $q->whereHas('Pendaftaran', function ($q) {
                        $q->where('branch_id', Auth::user()->branch_id);
                    });
                }
            });
            $q->where('status_pembayaran', false);
            $q->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'Completed');
            });
            $q->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap']);
        });
        $q->with(['rekamMedisPasien' => function ($q) {
            $q->whereIn('status_pemeriksaan', ['Boleh Pulang', 'Pasien Meninggal', 'Pulang Paksa']);
            $q->where(function ($q) {
                if (!Auth::user()->akses('global', null, false)) {
                    $q->whereHas('Pendaftaran', function ($q) {
                        $q->where('branch_id', Auth::user()->branch_id);
                    });
                }
            });
            $q->where('status_pembayaran', false);
            $q->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'Completed');
            });
            $q->withCount(['KamarRawatInapDanBedahDetail as is_rawat_inap']);
        }]);
    }])->count();

    return $pasienRawatInap + $pasien;
}

function permintaanStockActive()
{
    return \App\Models\PermintaanStock::orderBy('created_at', 'ASC')
        ->count();
}

function kekata($x)
{
    $x = abs($x);
    $angka = array(
        "", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"
    );
    $temp = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    } else if ($x < 20) {
        $temp = kekata($x - 10) . " belas";
    } else if ($x < 100) {
        $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
    } else if ($x < 200) {
        $temp = " seratus" . kekata($x - 100);
    } else if ($x < 1000) {
        $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
    } else if ($x < 2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x < 1000000) {
        $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
    } else if ($x < 1000000000) {
        $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
    } else if ($x < 1000000000000) {
        $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
    } else if ($x < 1000000000000000) {
        $temp = kekata($x / 1000000000000) . " trilyun" . kekata(fmod($x, 1000000000000));
    }
    return $temp;
}

function terbilang($x, $style = 4)
{
    if ($x < 0) {
        $hasil = "minus " . trim(kekata($x));
    } else {
        $hasil = trim(kekata($x));
    }
    switch ($style) {
        case 1:
            $hasil = strtoupper($hasil);
            break;
        case 2:
            $hasil = strtolower($hasil);
            break;
        case 3:
            $hasil = ucwords($hasil);
            break;
        default:
            $hasil = ucfirst($hasil);
            break;
    }
    return $hasil;
}

function CarbonParse($date = null, $format = 'Y-m-d')
{
    return \carbon\carbon::parse($date)->format($format);
}

function me()
{
    if (Auth::user() != null) {
        return Auth::user()->name;
    }
}

function dokter($status = null)
{
    return \App\Models\User::whereHas('role', function ($q) use ($status) {
        $q->where('type_role', 'DOKTER');
        if ($status != null) {
            $q->where('status', $status);
        }
    })->get();
}

function convertNumber($nominal, $decimal = false)
{
    if ($nominal == '') {
        return 0;
    }
    return $decimal ? filter_var($nominal, FILTER_SANITIZE_NUMBER_INT) / 100 :
        filter_var($nominal, FILTER_SANITIZE_NUMBER_INT);
}

function ruleResepRacikan($item, $d)
{
    $data = \App\Models\RuleResepRacikan::where('kategori_obat_id', $item->kategori_obat_id)
        ->where('satuan', 'BERAT')
        ->where('min', '<=', round($d->berat))
        ->where('max', '>=', round($d->berat))
        ->first();
    return $data;
}

function hari()
{
    return ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
}

function day()
{
    return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
}

function bulan()
{
    return ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
}

function month()
{
    return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
}

function convertDayToHari($day)
{
    $index = array_search($day, day());

    return hari()[$index];
}

function diffdate($start, $end)
{
    return ((strtotime($end) - strtotime($start)) / 86400);
}

function convertHariToDay($day)
{
    $index = array_search($day, hari());

    return day()[$index];
}

function convertMonthToBulan($month)
{
    $date = carbon\carbon::parse($month)->format('d F Y');
    $month = explode(' ', $date);

    $index = array_search($month[1], month());

    $bulan = bulan()[$index];

    return $month[0] . ' ' . $bulan . ' ' . $month[2];
}

function urlToSlug($string)
{
    $url = explode('/', $string);
    return $url[0];
}

function convertSlug($value, $param = 'Capital')
{

    switch ($param) {
        case 'Capital':
            $string = str_replace('-', ' ', $value);
            $string = str_replace('_', ' ', $string);
            $string =   ucwords(strtolower($string));
            return $string;
            break;
        case 'Camel Case':
            $string = str_replace('-', ' ', $value);
            $string = str_replace('_', ' ', $string);

            $string = explode(' ', $string);
            $temp = '';

            foreach ($string as  $i => $value) {
                if ($i == 0) {
                    $temp .= strtolower($value);
                } else {
                    $temp .= ucwords(strtolower($value));
                }
            }

            return $temp;
            break;
        case 'Lower Case':
            return str_replace(' ', '', strtolower($value));
            break;
        case 'Upper Case':
            return str_replace(' ', '', strtoupper($value));
            break;
        default:
            break;
    }
}

function rekonStock($jenis, $itemId)
{
    $column = $jenis == 'OBAT' ? 'produk_obat_id' : 'item_non_obat_id';
    $checkStock = \App\Models\Stock::where('jenis_stock', $jenis)
        ->where($column, $itemId)
        ->get();

    foreach ($checkStock as $d) {
        $mutasiStock = \App\Models\MutasiStock::where('stock_id', $d->id)
            ->where('jenis', 'PENERIMAAN')
            ->sum('qty_tersisa');
        \App\Models\Stock::where('id', $d->id)
            ->update([
                'qty' => $mutasiStock,
                'updated_by' => me(),
            ]);
    }
}

function revertStock($id,  $table)
{
    switch ($table) {
        case 'ms_pengeluaran_stock_detail_mutasi':
            $data = \App\Models\PengeluaranStockDetailMutasi::where('pengeluaran_stock_id', $id)
                ->get();
            foreach ($data as $i => $d) {
                $checkMutasi = \App\Models\MutasiStock::find($d->mutasi_stock_id);

                if ($checkMutasi) {
                    \App\Models\MutasiStock::where('id', $checkMutasi->id)
                        ->update([
                            'qty_tersisa' => $checkMutasi->qty_tersisa + $d->qty
                        ]);
                }
            }
        default:
            # code...
            break;
    }
    return true;
}

function pasienHariIni($tanggal = null)
{
    $tanggal = $tanggal ? $tanggal : now();
    $data = \App\Models\RekamMedisPasien::where('status', true)
        ->where(function ($q) {
            if (Auth::user()->aksesMenu('global', '/')) {
                $q->whereHas('Pendaftaran', function ($q) {
                    $q->where('branch_id', Auth::user()->branch_id);
                });
            }
        })
        ->whereHas('Pendaftaran', function ($q) use ($tanggal) {
            $q->where('tanggal', CarbonParse($tanggal, 'Y-m-d'));
        })
        ->count();
    return $data;
}

function pasienBulanIni($tanggal = null)
{
    $tanggal = $tanggal ? $tanggal : now();
    $data = \App\Models\RekamMedisPasien::where('status', true)
        ->where(function ($q) {
            if (Auth::user()->aksesMenu('global', '/')) {
                $q->whereHas('Pendaftaran', function ($q) {
                    $q->where('branch_id', Auth::user()->branch_id);
                });
            }
        })
        ->whereHas('Pendaftaran', function ($q) use ($tanggal) {
            $q->where('tanggal', '>=', carbon\carbon::parse($tanggal)->startOfMonth()->format('Y-m-d'));
            $q->where('tanggal', '<=', carbon\carbon::parse($tanggal)->endOfMonth()->format('Y-m-d'));
        })
        ->count();
    return $data;
}

function sedangDirawat()
{
    $data = \App\Models\RekamMedisPasien::where('status', true)
        ->where(function ($q) {
            if (Auth::user()->aksesMenu('global', '/')) {
                $q->whereHas('Pendaftaran', function ($q) {
                    $q->where('branch_id', Auth::user()->branch_id);
                });
            }
        })
        ->whereHas('KamarRawatInapDanBedahDetail', function ($q) {
            $q->where('status', 'In Use');
        })
        ->count();
    return $data;
}

function pasienBedahHariIni()
{
    return  \App\Models\RekamMedisPasien::whereHas('RekamMedisRekomendasiTindakanBedah', function ($q) {
        $q->where('tanggal_rekomendasi_bedah', dateStore());
    })->where(function ($q) {
        if (Auth::user()->aksesMenu('global', '/')) {
            $q->whereHas('Pendaftaran', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });
        }
    })->count();
}

function pasienWaitingListBedahHariIni()
{
    return  \App\Models\RekamMedisPasien::whereHas('RekamMedisRekomendasiTindakanBedah', function ($q) {
        $q->where('tanggal_rekomendasi_bedah', dateStore());
        $q->where('status', "Released");
    })->where(function ($q) {
        if (Auth::user()->aksesMenu('global', '/')) {
            $q->whereHas('Pendaftaran', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });
        }
    })->count();
}

function romawi($angka)
{

    $hsl = "";
    if ($angka < 1 || $angka > 3999) {
        $hsl = "Batas Angka 1 s/d 3999";
    } else {
        while ($angka >= 1000) {
            $hsl .= "M";
            $angka -= 1000;
        }
        if ($angka >= 500) {
            if ($angka > 500) {
                if ($angka >= 900) {
                    $hsl .= "M";
                    $angka -= 900;
                } else {
                    $hsl .= "D";
                    $angka -= 500;
                }
            }
        }
        while ($angka >= 100) {
            if ($angka >= 400) {
                $hsl .= "CD";
                $angka -= 400;
            } else {
                $angka -= 100;
            }
        }
        if ($angka >= 50) {
            if ($angka >= 90) {
                $hsl .= "XC";
                $angka -= 90;
            } else {
                $hsl .= "L";
                $angka -= 50;
            }
        }
        while ($angka >= 10) {
            if ($angka >= 40) {
                $hsl .= "XL";
                $angka -= 40;
            } else {
                $hsl .= "X";
                $angka -= 10;
            }
        }
        if ($angka >= 5) {
            if ($angka == 9) {
                $hsl .= "IX";
                $angka -= 9;
            } else {
                $hsl .= "V";
                $angka -= 5;
            }
        }
        while ($angka >= 1) {
            if ($angka == 4) {
                $hsl .= "IV";
                $angka -= 4;
            } else {
                $hsl .= "I";
                $angka -= 1;
            }
        }
    }
    return ($hsl);
}

function decreasingStock($jenis, $itemId, $branchId, $qty, $ref = null)
{
    $column = $jenis == 'OBAT' ? 'produk_obat_id' : 'item_non_obat_id';
    $stock = \App\Models\Stock::where('jenis_stock', $jenis)
        ->where($column, $itemId)
        ->where('branch_id', $branchId)
        ->first();
    $totalPengeluaran = 0;
    $mutasi = [];
    if ($stock) {
        $mutasiStock  = \App\Models\MutasiStock::where('jenis', 'PENERIMAAN')
            ->where('stock_id', $stock->id)
            ->where('qty_tersisa', '>', 0)
            ->orderBy('created_at', 'ASC')
            ->get();

        $tempQty = $qty;
        foreach ($mutasiStock as $i => $d) {
            if ($tempQty > 0) {
                $mutasiStockQtyTersisa = $d->qty_tersisa;
                $mutasiStockQtyTersisa -= $tempQty;
                if ($mutasiStockQtyTersisa > 0) {
                    $totalPengeluaran += $d->harga_satuan * $tempQty;
                    $tempMutasi['total'] =  $d->harga_satuan * $tempQty;
                    $tempMutasi['harga'] =  $d->harga_satuan;
                    $tempMutasi['item_id'] =  $itemId;
                    $tempQty = 0;
                } else {
                    $totalPengeluaran += $d->harga_satuan * $d->qty_tersisa;
                    $tempMutasi['total'] =  $d->harga_satuan * $d->qty_tersisa;
                    $tempMutasi['harga'] =  $d->harga_satuan;
                    $tempMutasi['item_id'] =  $itemId;
                    $mutasiStockQtyTersisa = 0;
                    $tempQty -= $d->qty_tersisa;
                }

                $tempMutasi['id'] = $d->id;
                $tempMutasi['qty'] = $d->qty_tersisa - $mutasiStockQtyTersisa;
                array_push($mutasi, $tempMutasi);
                $idMutasiStock = \App\Models\MutasiStock::max('id') + 1;

                \App\Models\MutasiStock::create([
                    'stock_id'  => $stock->id,
                    'id'    => $idMutasiStock,
                    'harga_satuan'  => $d->harga_satuan,
                    'total_harga'   => ($d->qty_tersisa -  $mutasiStockQtyTersisa) * $d->harga_satuan,
                    'qty'   => $d->qty_tersisa -  $mutasiStockQtyTersisa,
                    'qty_tersisa'   => 0,
                    'referensi' => $ref,
                    'jenis' => 'PENGELUARAN',
                    'created_by'    => me(),
                    'updated_by'    => me(),
                ]);

                \App\Models\MutasiStock::where('id', $d->id)
                    ->update([
                        'qty_tersisa' => $mutasiStockQtyTersisa,
                    ]);
            }
        }

        $mutasiStock = \App\Models\MutasiStock::where('stock_id', $stock->id)
            ->where('jenis', 'PENERIMAAN')
            ->sum('qty_tersisa');

        \App\Models\Stock::where('id', $stock->id)
            ->update([
                'qty' => $mutasiStock,
                'updated_by' => me(),
            ]);
    }

    return Response()->json(['total' => $totalPengeluaran, 'mutasi' => $mutasi]);
}

function notification()
{
    # code...
}

function generateStock()
{
    $data = \App\Models\Branch::where('status', true)->get();
    $itemNonObat = \App\Models\ItemNonObat::where('status', true)->get();
    $produkObat = \App\Models\ProdukObat::where('status', true)->get();

    foreach ($data as $i => $d) {
        foreach ($itemNonObat as $i1 => $d1) {
            $check = \App\Models\Stock::where('jenis_stock', 'NON OBAT')
                ->where('item_non_obat_id', $d1->id)
                ->where('branch_id', $d->id)
                ->first();
            if (!$check) {
                $id = \App\Models\Stock::max('id') + 1;
                \App\Models\Stock::create(
                    [
                        'id'    => $id,
                        'jenis_stock'   => 'NON OBAT',
                        'branch_id' => $d->id,
                        'item_non_obat_id'  => $d1->id,
                        'qty'   => 0,
                        'created_by'    => me(),
                        'updated_by'    => me(),
                    ]
                );
            }
        }

        foreach ($produkObat as $i1 => $d1) {
            $check = \App\Models\Stock::where('jenis_stock', 'OBAT')
                ->where('produk_obat_id', $d1->id)
                ->where('branch_id', $d->id)
                ->first();
            if (!$check) {
                $id = \App\Models\Stock::max('id') + 1;
                \App\Models\Stock::create(
                    [
                        'id'    => $id,
                        'jenis_stock'   => 'OBAT',
                        'branch_id' => $d->id,
                        'produk_obat_id'  => $d1->id,
                        'qty'   => 0,
                        'created_by'    => me(),
                        'updated_by'    => me(),
                    ]
                );
            }
        }
    }

    return 'Success Generating Stock';
}

function dot()
{
    echo '<i class="text-red-500">*</i>';
}

function persentaseKamar()
{

    $data = \App\Models\KamarRawatInapDanBedah::where('status', true)
        ->where(function ($q) {
            if (!Auth::user()->akses('global')) {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })
        ->withCount(['kamarRawatInapDanBedahDetail as terpakai' => function ($q) {
            $q->where('status', 'In Use');
        }])
        ->get();
    $kapasitas = 0;
    $terpakai = 0;
    foreach ($data as $key => $value) {
        $kapasitas += $value->kapasitas;
        $terpakai += $value->terpakai;
    }
    $persentase = ($terpakai / $kapasitas * 100);
    return $persentase;
}

function queryStatus($code)
{
    switch ($code) {
        case '23505':
            return Response()->json(['status' => 2, 'message' => 'Data duplikasi'], 500);
            break;
        case '23500':
            return Response()->json(['status' => 2, 'message' => 'Data sudah memiliki relasi data'], 500);
            break;
        case '23503':
            return Response()->json(['status' => 2, 'message' => 'Data ini sudah memiliki relasi dengan data yang lain.'], 500);
            break;
        default:
            return Response()->json(['status' => 2, 'message' => $code->getMessage()], 500);
            break;
    }
}


function infoPasien($rm)
{
    $infoPasien = \App\Models\PendaftaranPasien::where('pendaftaran_id', $rm->pendaftaran_id)
        ->where('pasien_id', $rm->pasien_id)
        ->first();

    return $infoPasien;
}
