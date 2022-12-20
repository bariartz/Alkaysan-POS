<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\ItemTransaksi;
use App\Models\BayarTransaksi;
use App\Models\Fungsi;
use App\Models\Kas;
use App\Models\MasterAkun;
use App\Models\MasterItem;
use App\Models\MasterKas;
use App\Models\perusahaan;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index($cabang)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        return view('penjualan', [
            "title" => "Penjualan",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "transaksi" => Transaksi::whereDate("Tanggal_Transaksi", "=", date("Y-m-d"))->orderBy("Tanggal_Transaksi", "DESC")->get()
        ]);
    }

    public function tabel_index()
    {
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        return view('tabelpenjualan', [
            "title" => "Penjualan",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "transaksi" => Transaksi::whereDate("Tanggal_Transaksi", "=", date("Y-m-d"))->orderBy("Tanggal_Transaksi", "DESC")->get()
        ]);
    }

    public function kas_index()
    {
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $kas = KAS::whereDate("Tanggal", "=", date("Y-m-d"))->orderBy("Tanggal", "DESC")->get();
        return view('lap-kas', [
            "title" => "Kas",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "kas" => $kas
        ]);
    }

    public function kas_index_full()
    {
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $kas = KAS::whereDate("Tanggal", "=", date("Y-m-d"))->orderBy("Tanggal", "DESC")->get();
        return view('tabelkas', [
            "title" => "Kas",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "kas" => $kas
        ]);
    }

    public function kas_index_tgl($tgl)
    {
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $getKas = DB::table('kas')->join('bayar_transaksis', 'kas.Dokumen', '=', 'bayar_transaksis.no_bayar')->join('transaksis', 'transaksis.No_Transaksi', '=', 'bayar_transaksis.no_transaksi')->whereDate('kas.Tanggal', '=', $tgl)->orderBy('kas.Tanggal', 'DESC')->get();
        return view('tabelkas', [
            "title" => "Kas",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "kas" => $getKas
        ]);
    }

    public function kas_index_sort($cabang, $kodekas, $tglawal, $tglakhir)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        if ($kodekas == "ALL") {
            $getKas = KAS::whereRaw("DATE(Tanggal) BETWEEN DATE('$tglawal') AND DATE('$tglakhir')")->orderBy("Tanggal", "DESC")->get();
        } else {
            $getKas = KAS::whereRaw("DATE(Tanggal) BETWEEN DATE('$tglawal') AND DATE('$tglakhir')")->where("kode_kas", "=", $kodekas)->orderBy("Tanggal", "DESC")->get();
        }
        return view('tabelkas', [
            "title" => "Kas",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "kas" => $getKas
        ]);
    }

    public function penjualan_index_sort($tglawal, $tglakhir)
    {
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $transaksi = Transaksi::whereRaw("DATE(Tanggal_Transaksi) BETWEEN DATE('$tglawal') AND DATE('$tglakhir')")->orderBy("Tanggal_Transaksi", "DESC")->get();

        return view('tabelpenjualan', [
            "title" => "Penjualan",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "transaksi" => $transaksi
        ]);
    }

    public function index_id($cabang, $noTransaksi)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $ItemTransaksi = ItemTransaksi::where("No_Transaksi", $noTransaksi)->get();
        $transaksi = Transaksi::where("No_Transaksi", $noTransaksi)->first();
        return view('transaksi', [
            "title" => "Transaksi - " . $transaksi->No_Transaksi,
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "transaksi" => $transaksi,
            "itemtransaksi" => $ItemTransaksi
        ]);
    }

    public function index_id_form($cabang, $noTransaksi)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $ItemTransaksi = ItemTransaksi::where("No_Transaksi", $noTransaksi)->get();
        $transaksi = Transaksi::where("No_Transaksi", $noTransaksi)->first();
        return view('form-edit', [
            "title" => "Transaksi - " . $noTransaksi,
            "cabang" => $shortCabang,
            "nama_user" => $usersData->nama_belakang_karyawan !== null ? $usersData->nama_depan_karyawan . ' ' .  $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan,
            "foto_user" => $usersData->photo_karyawan,
            "transaksi" => $transaksi,
            "itemtransaksi" => $ItemTransaksi
        ]);
    }

    public function index_add($cabang)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        return view('tambah-transaksi', [
            "title" => "Penjualan",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
        ]);
    }

    public function struk_penjualan($cabang, $noTransaksi)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $transaksi = Transaksi::where("No_Transaksi", $noTransaksi)->first();
        return view('report/struk-penjualan', [
            "title" => "Cetak Struk Penjualan " . $transaksi->No_Transaksi,
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "transaksi" => $transaksi,
            "getTransaksi" => ItemTransaksi::where("No_Transaksi", "=", $transaksi->No_Transaksi)->get(),
            "qrcode" => QrCode::generate('https://tracking.alkaysan.com/' . $shortCabang . "/" .  $transaksi->No_Transaksi),
            "perusahaan" => $dataPerusahaan['data']['0']
        ]);
    }

    public function cekNoTransaksi()
    {
        $getNoTransaksi = Transaksi::latest("No_Transaksi")->first();
        return $getNoTransaksi;
    }

    public function master_item()
    {
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $data = DB::table('produk_hargas')->join('master_items', 'master_items.Kode_Produk',  '=', 'produk_hargas.Kode_Produk')->selectRaw("DISTINCT(produk_hargas.Kode_Produk), master_items.Nama_Produk, master_items.Kategori, master_items.Satuan, produk_hargas.min_pembelian")->orderBy('master_items.Nama_Produk', 'ASC')->orderBy('produk_hargas.min_pembelian', 'ASC')->get();
        return view('master-item', [
            "title" => "Master Item",
            "cabang" => $shortCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "data" => $data
        ]);
    }

    public function __invoke()
    {
        //
    }
}
