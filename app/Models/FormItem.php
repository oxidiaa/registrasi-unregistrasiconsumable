<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormItem extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga',
        'estimasi_usia_pakai',
        'kategori_penggunaan',
        'kategori_ukuran',
        'min',
        'titik_order',
        'max',
        'lead_time',
        'is_b3',
        'is_non_b3',
    ];

    protected $casts = [
        'is_b3'    => 'boolean',
        'is_non_b3' => 'boolean',
        'harga'    => 'decimal:2',
    ];
}
