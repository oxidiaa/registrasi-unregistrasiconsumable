<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\FormItem;

class FormItemTest extends TestCase
{
    /**
     * Test that if price is Rp 5.000.000 or below, it is always NO ASET.
     */
    public function test_price_five_million_or_less_is_no_aset(): void
    {
        $item = new FormItem([
            'harga' => 5000000,
            'estimasi_usia_pakai' => '730',
        ]);
        $this->assertEquals('NO ASET', $item->kategori_aset);

        $item2 = new FormItem([
            'harga' => 4999999,
            'estimasi_usia_pakai' => '800 hari',
        ]);
        $this->assertEquals('NO ASET', $item2->kategori_aset);

        $item3 = new FormItem([
            'harga' => 100000,
            'estimasi_usia_pakai' => '1000',
        ]);
        $this->assertEquals('NO ASET', $item3->kategori_aset);
    }

    /**
     * Test various inputs for estimated useful life that should be classified as ASET.
     */
    public function test_price_above_five_million_and_useful_life_seven_hundred_thirty_days_or_more_is_aset(): void
    {
        $cases = [
            '730',
            '730 hari',
            '730 Hari',
            '730.5',
            '730,5 hari',
            '800',
            '1000 hari',
        ];

        foreach ($cases as $case) {
            $item = new FormItem([
                'harga' => 5000001,
                'estimasi_usia_pakai' => $case,
            ]);
            $this->assertEquals('ASET', $item->kategori_aset, "Failed for case: {$case}");
        }
    }

    /**
     * Test various inputs for estimated useful life that should be classified as NO ASET.
     */
    public function test_price_above_five_million_but_useful_life_less_than_seven_hundred_thirty_days_is_no_aset(): void
    {
        $cases = [
            '729',
            '729 hari',
            '365',
            '365 Hari',
            '1',
            '0',
            '',
            null,
        ];

        foreach ($cases as $case) {
            $item = new FormItem([
                'harga' => 6000000,
                'estimasi_usia_pakai' => $case,
            ]);
            $this->assertEquals('NO ASET', $item->kategori_aset, "Failed for case: {$case}");
        }
    }
}
