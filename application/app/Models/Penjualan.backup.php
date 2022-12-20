<?php

namespace App\Models;

class Penjualan
{
    private static $transaksi = [];

    public static function get_transaksi()
    {
        return collect(self::$transaksi);
    }

    public static function transaksi($no_transaksi)
    {
        $transaksiItem = static::get_transaksi();
        return $transaksiItem->firstWhere('no_transaksi', $no_transaksi);
    }
}
