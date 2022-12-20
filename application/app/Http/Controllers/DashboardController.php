<?php

namespace App\Http\Controllers;

use App\Models\BayarTransaksi;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Kas;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use App\Models\Fungsi;
use App\Models\ItemTransaksi;
use App\Models\Produk;
use App\Models\MasterItem;
use App\Models\perusahaan;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        $cabang = Session::get('store');
        if($cabang == null){
            return Inertia::render('Welcome', [
                'flash' => [
                    'message' => $request->session()->get('login_message')
                ],
                'store' => $cabang,
            ]);
        }

        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        $penghasilanHarian = Transaksi::whereDate('Tanggal_Transaksi', date('Y-m-d'))->sum('total_bayar');
        $penghasilanBulanan = Transaksi::whereMonth('Tanggal_Transaksi', date('m'))->whereYear('Tanggal_Transaksi', date('Y'))->sum('total_bayar');
        $a3_click = DB::table("transaksis")->join('item_transaksis', 'item_transaksis.No_Transaksi', '=', 'transaksis.No_Transaksi')->join('master_items', 'master_items.Kode_Produk', '=', 'item_transaksis.Kode_Produk')->whereIn('master_items.Kategori', ['A3', 'OFFSET'])->whereMonth('item_transaksis.Tanggal_Transaksi', date("m"))->whereYear('item_transaksis.Tanggal_Transaksi', date('Y'))->sum('item_transaksis.Qty');
        $a3_click_old = DB::table("transaksis")->join('item_transaksis', 'item_transaksis.No_Transaksi', '=', 'transaksis.No_Transaksi')->join('master_items', 'master_items.Kode_Produk', '=', 'item_transaksis.Kode_Produk')->where('master_items.Kategori', '=', 'A3')->whereMonth('item_transaksis.Tanggal_Transaksi', date("m", strtotime("-1 month")))->whereYear('item_transaksis.Tanggal_Transaksi', date('Y'))->sum('item_transaksis.Qty');
        
        // Status Orderan Bulanan
        $belumdiproses = Transaksi::whereMonth('Tanggal_Transaksi', date('m'))->whereYear('Tanggal_Transaksi', date('Y'))->where('Status_Transaksi', '=', 'Belum diproses')->count('Status_Transaksi');
        $belumdiprosesharian = Transaksi::whereDate('Tanggal_Transaksi', date('Y-m-d'))->where('Status_Transaksi', '=', 'Belum diproses')->count('Status_Transaksi');
        $sedangdidesain = Transaksi::whereMonth('Tanggal_Transaksi', date('m'))->whereYear('Tanggal_Transaksi', date('Y'))->where('Status_Transaksi', '=', 'Sedang didesain')->count('Status_Transaksi');
        $prosescetak = Transaksi::whereMonth('Tanggal_Transaksi', date('m'))->whereYear('Tanggal_Transaksi', date('Y'))->where('Status_Transaksi', '=', 'Proses cetak')->count('Status_Transaksi');
        $selesaidicetak = Transaksi::whereMonth('Tanggal_Transaksi', date('m'))->whereYear('Tanggal_Transaksi', date('Y'))->where('Status_Transaksi', '=', 'Selesai dicetak')->count('Status_Transaksi');
        $sudahdiambil = Transaksi::whereMonth('Tanggal_Transaksi', date('m'))->whereYear('Tanggal_Transaksi', date('Y'))->where('Status_Transaksi', '=', 'Sudah diambil')->count('Status_Transaksi');
        $getKategoriNow = DB::table('master_items')->selectRaw('master_items.Kategori, SUM(item_transaksis.Qty) as total_kategori, master_items.Satuan')->join('item_transaksis', 'master_items.Kode_Produk', '=', 'item_transaksis.Kode_Produk')->whereMonth('item_transaksis.Tanggal_Transaksi', date("m"))->whereYear('item_transaksis.Tanggal_Transaksi', date('Y'))->groupBy('master_items.Kategori')->get();
        $getKategori = DB::table('master_items')->selectRaw('master_items.Kategori, SUM(item_transaksis.Qty) as total_kategori, master_items.Satuan')->join('item_transaksis', 'master_items.Kode_Produk', '=', 'item_transaksis.Kode_Produk')->whereMonth('item_transaksis.Tanggal_Transaksi', date("m", strtotime("-1 month")))->whereYear('item_transaksis.Tanggal_Transaksi', date('Y'))->groupBy('master_items.Kategori')->get();
        $getKategoriOld = DB::table('master_items')->selectRaw('master_items.Kategori, SUM(item_transaksis.Qty) as total_kategori, master_items.Satuan')->join('item_transaksis', 'master_items.Kode_Produk', '=', 'item_transaksis.Kode_Produk')->whereMonth('item_transaksis.Tanggal_Transaksi', date('m', strtotime("-2 month")))->whereYear('item_transaksis.Tanggal_Transaksi', date('Y'))->groupBy('master_items.Kategori')->get();
        $valuePerMonth = Transaksi::selectRaw("SUM(`total_bayar`) AS penghasilan")->whereYear('Tanggal_Transaksi', date("Y"))->groupBy(DB::raw("MONTH(`Tanggal_Transaksi`)"))->get();
        $hppPerMonth = DB::table("transaksis")->join("item_transaksis", "item_transaksis.No_Transaksi", "=", "transaksis.No_Transaksi")->join("master_items", "master_items.Kode_Produk", "=", "item_transaksis.Kode_Produk")->selectRaw("SUM(`master_items`.`harga_beli`) AS hpp")->whereYear('transaksis.Tanggal_Transaksi', date("Y"))->groupBy(DB::raw("MONTH(`transaksis`.`Tanggal_Transaksi`)"))->get();
        $pengeluaranPerMonth = Kas::selectRaw("SUM(`Keluar`) AS pengeluaran")->whereYear('Tanggal', date("Y"))->groupBy(DB::raw("MONTH(`Tanggal`)"))->get();

        return Inertia::render('Admin/Main', [
            "store" => $shortCabang,
            "nama_user" => $usersData['nama_belakang_karyawan'] !== NULL ? $usersData['nama_depan_karyawan'] . ' ' . $usersData['nama_belakang_karyawan'] : $usersData['nama_depan_karyawan'],
            "foto_user" => $usersData['foto'],
            "penghasilan_harian" => Fungsi::rupiah($penghasilanHarian),
            "penghasilan_bulanan" => Fungsi::rupiah($penghasilanBulanan),
            "a3" => $a3_click,
            "a3_bulan_lalu" => $a3_click_old,
            "belumdiproses" => $belumdiproses,
            "belumdiprosesharian" => $belumdiprosesharian,
            "sedangdidesain" => $sedangdidesain,
            "prosescetak" => $prosescetak,
            "selesaidicetak" => $selesaidicetak,
            "sudahdiambil" => $sudahdiambil,
            "kategorinow" => $getKategoriNow,
            "kategoriproduk" => $getKategori,
            "kategoriprodukbulanlalu" => $getKategoriOld,
            "totalpenghasilan" => $valuePerMonth,
            "totalhpp" => $hppPerMonth,
            "totalpengeluaran" => $pengeluaranPerMonth
        ]);
    }

    public function pilihCabang()
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('accessby=' . $usersData->id_karyawan);
        $countCabangHave = count($dataPerusahaan['data']);

        if($countCabangHave == 1){
            return redirect('/' . strtolower($dataPerusahaan['data'][0]['folder']) . '/dashboard');
        }

        return view('pilih-cabang', [
            'cabangData' => $dataPerusahaan,
            'cabang' => 'Alkaysan',
        ]);
    }

    public function goStore(Request $request)
    {
        $store = $request->store;
        if($request->session()->get('store') !== '' || !empty($request->session()->get('store'))) {
            $store = $request->session()->get('store');
        }
        
        $request->session()->put('store', $store);

        return response()->json(['success' => true, 'store' => $store], 200);
    }
}
