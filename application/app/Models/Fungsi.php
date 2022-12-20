<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Fungsi
{
    public static function convertBulanShort($bulan)
    {
        switch (ucfirst($bulan)) {
            case "Jan":
                return " Jan ";
                break;
            case "Feb":
                return " Feb ";
                break;
            case "Mar":
                return " Mar ";
                break;
            case "Apr":
                return " Apr ";
                break;
            case "May":
                return " Mei ";
                break;
            case "Jun":
                return " Jun ";
                break;
            case "Jul":
                return " Jul ";
                break;
            case "Aug":
                return " Ags ";
                break;
            case "Sep":
                return " Sep ";
                break;
            case "Oct":
                return " Okt ";
                break;
            case "Nov":
                return " Nov ";
                break;
            case "Dec":
                return " Des ";
                break;
        }
    }

    public static function convertBulanLong($bulan)
    {
        switch (ucfirst($bulan)) {
            case "January":
                return " Januari ";
                break;
            case "February":
                return " Februari ";
                break;
            case "March":
                return " Maret ";
                break;
            case "April":
                return " April ";
                break;
            case "May":
                return " Mei ";
                break;
            case "June":
                return " Juni ";
                break;
            case "July":
                return " Juli ";
                break;
            case "August":
                return " Agustus ";
                break;
            case "September":
                return " September ";
                break;
            case "October":
                return " Oktober ";
                break;
            case "December":
                return " Desember ";
                break;
        }
    }

    public static function convertNamaHari($hari)
    {
        switch ($hari) {
            case "Monday":
                return "Minggu";
                break;
            case "Sunday":
                return "Senin";
                break;
            case "Tuesday":
                return "Selasa";
                break;
            case "Wednesday":
                return "Rabu";
                break;
            case "Thursday":
                return "Kamis";
                break;
            case "Friday":
                return "Jumat";
                break;
            case "Saturday":
                return "Sabtu";
                break;
        }
    }

    public static function rupiah($price)
    {

        $priceData = number_format($price, 2, ',', '.');
        preg_match_all("/[^,]+/", $priceData, $priceFormat);
        if ($price == 0 || $price == NULL || $price == "" || empty($price)) {
            $price = "-";
        } else {
            $price = "Rp " . $priceFormat[0][0];
        }
        return $price;
    }

    public static function rupiahNoSymbol($price)
    {

        $priceData = number_format($price, 2, ',', '.');
        preg_match_all("/[^,]+/", $priceData, $priceFormat);
        if ($price == 0 || $price == NULL || $price == "" || empty($price)) {
            $price = "-";
        } else {
            $price = $priceFormat[0][0];
        }
        return $price;
    }

    public static function decimalNumb($num)
    {

        $numData = number_format($num, 2, ',', '');
        preg_match_all("/[^,]+/", $numData, $numFormat);
        if ($num == 0 || $num == NULL || $num == "" || empty($num)) {
            $num = 0;
        } else {
            $num = $numFormat[0][0];
        }
        return $num;
    }

    public static function colorLbl()
    {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];
        return $color;
    }

    public static function switchJnsBayar($jnsBayar)
    {
        switch (strtolower($jnsBayar)) {
            case "tunai":
                return "Pembayaran Tunai";
                break;
            case "bsi":
                return "Pembayaran via Transfer BSI";
                break;
        }
    }

    public static function riwayatTransaksi($no_transaksi)
    {
        $getTransaksi = DB::table('bayar_transaksis')
            ->join('kas', "kas.Dokumen", "=", "bayar_transaksis.no_bayar")
            ->where("bayar_transaksis.no_transaksi", "=", $no_transaksi)
            ->get();
        if (!empty($getTransaksi->no_transaksi)) {
            return [
                'bayartransaksi' => null
            ];
        } else {
            return [
                'bayartransaksi' => $getTransaksi
            ];
        }
    }

    public static function classSB($Status_Bayar)
    {
        switch ($Status_Bayar) {
            case "Lunas":
                return "table-success";
                break;
            case "Belum Lunas":
                return "table-warning";
                break;
            case "-":
                return "table-default";
                break;
        }
    }

    public static function classSBMobile($Status_Bayar)
    {
        switch ($Status_Bayar) {
            case "Lunas":
                return "table-success";
                break;
            case "Belum Lunas":
                return "table-warning";
                break;
            case "-":
                return "table-default";
                break;
        }
    }

    public static function getUsersDataFromApp($username)
    {
        $getDataUserFromDB = User::where("username", "=", $username)->first();
        if ($getDataUserFromDB) {
            return $getDataUserFromDB;
        } else {
            $getDataUser = file_get_contents("https://app.alkaysan.com/api/v1/oauth?username=" . $username);
            $encJson = json_decode($getDataUser, true);
            User::create([
                "nama_user" => $encJson['nama_user'],
                "username" => $encJson['username'],
                "foto" => "https://app.alkaysan.co.id" . $encJson['foto'],
                "cabang" => $encJson['cabang'],
                "jabatan" => $encJson['jabatan'],
                "tanggal_input" => now()
            ]);
            return $encJson;
        }
    }

    public static function hp($nohp)
    {
        // kadang ada penulisan no hp 0811 239 345
        $nohp = str_replace(" ", "", $nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace("(", "", $nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace(")", "", $nohp);
        // kadang ada penulisan no hp 0811.239.345
        $nohp = str_replace(".", "", $nohp);

        // cek apakah no hp mengandung karakter + dan 0-9
        if (!preg_match('/[^+0-9]/', trim($nohp))) {
            // cek apakah no hp karakter 1-3 adalah +62
            if (substr(trim($nohp), 0, 3) == '+62') {
                $nohp = trim($nohp);
            }
            // cek apakah no hp karakter 1 adalah 0
            elseif (substr(trim($nohp), 0, 1) == '0') {
                $nohp = '62' . substr(trim($nohp), 1);
            }
        }
        return $nohp;
    }
}
