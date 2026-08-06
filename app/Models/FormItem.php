<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormItem extends Model
{
    protected $fillable = [
        'form_number',
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

    /**
     * Determine if the item is classified as ASET or NO ASET.
     * Logic: Harga > Rp 5.000.000 and Estimasi Usia Pakai >= 730 Hari (Per hari).
     */
    public function getKategoriAsetAttribute(): string
    {
        $harga = (float) $this->harga;
        if ($harga <= 4999999) {
            return 'NO ASET';
        }

        $usia = trim($this->estimasi_usia_pakai ?? '');
        if ($usia === '') {
            return 'NO ASET';
        }

        // Normalize decimals (e.g. 730,5 to 730.5)
        $usia = str_replace(',', '.', $usia);

        // Match the first numeric sequence (integer or float)
        if (preg_match('/(\d+(?:\.\d+)?)/', $usia, $matches)) {
            $days = (float) $matches[1];
            return $days >= 730 ? 'ASET' : 'NO ASET';
        }

        return 'NO ASET';
    }
}
