<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\ItemTransaksi;
use App\Models\BayarTransaksi;
use App\Models\Fungsi;
use App\Models\Kas;
use App\Models\MasterAkun;
use App\Models\MasterItem;
use App\Models\MasterKas;
use App\Models\perusahaan;
use App\Models\ProdukHarga;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use PDF;

class API extends Controller
{
    public function index_transaksi($tglawal, $tglakhir, $limit)
    {
        $kelipatanLimit = 25;
        $transaksi = Transaksi::whereRaw("DATE(Tanggal_Transaksi) BETWEEN '$tglawal' AND '$tglakhir'")->orderBy("Tanggal_Transaksi", "DESC")->offset($limit - $kelipatanLimit)->limit($limit)->get();
        $totalResult = Transaksi::selectRaw("COUNT(*) as total_result")->whereRaw("DATE(Tanggal_Transaksi) BETWEEN '$tglawal' AND '$tglakhir'")->first();
        $items = [];

        foreach ($transaksi as $item) {
            $item = [
                "no_transaksi" => $item->No_Transaksi,
                "nama_pemesan" => $item->nama_pemesan,
                "tgl_transaksi" => $item->Tanggal_Transaksi,
                "status_bayar" => $item->Status_Bayar,
                "status_transaksi" => $item->Status_Transaksi,
                "total_qty" => (float)$item->total_qty,
                "total_item" => (float)$item->total_item,
                "grandtotal" => Fungsi::rupiah($item->net_total_sales),
                "total_bayar" => Fungsi::rupiah($item->total_bayar),
                "sisa_bayar" => Fungsi::rupiah($item->sisa_bayar),
                "nama_cs" => $item->nama_cs
            ];

            $items[] = $item;
        }

        return response()->json([
            'total_page' => round($totalResult->total_result),
            'transaksi' => ($transaksi->isEmpty() ? null : $items)
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function search_transaksi($cabang, $no_transaksi)
    {
        $transaksi = Transaksi::where("No_Transaksi", "LIKE", "%$no_transaksi%")->orWhere("nama_pemesan", "LIKE", "%$no_transaksi%")->orWhere("telepon_pemesan", "LIKE", "%$no_transaksi%")->orderBy("Tanggal_Transaksi", "DESC")->get();
        //$itemTransaksi = ItemTransaksi::where("No_Transaksi", "LIKE", "%$no_transaksi%")->orWhere("Nama_Produk", "LIKE", "%$no_transaksi%")->orWhere("keterangan", "LIKE", "%$no_transaksi%")->orderBy("Tanggal_Transaksi", "DESC")->get();
        $itemTransaksi = DB::table("master_items")->join('item_transaksis', 'item_transaksis.Kode_Produk', '=', 'master_items.Kode_Produk')
            ->where('master_items.Kategori', 'LIKE', "%$no_transaksi%")
            ->orWhere('item_transaksis.No_Transaksi', 'LIKE', "%$no_transaksi%")
            ->orWhere('item_transaksis.Nama_Produk', 'LIKE', "%$no_transaksi%")
            ->orWhere('item_transaksis.keterangan', 'LIKE', "%$no_transaksi%")->orderBy('item_transaksis.Tanggal_Transaksi', 'DESC')->get();
        $kodekas = MasterKas::orderBy('nama', 'ASC')->get();
        $items = [];
        $kasItem = [];
        $itemTr = [];

        if ($transaksi->isEmpty()) {
            foreach ($itemTransaksi as $itemtransaksi) {
                $itemTr = [
                    "nama_produk" => $itemtransaksi->Nama_Produk,
                    "keterangan" => $itemtransaksi->keterangan
                ];

                $transaksi = Transaksi::where("No_Transaksi", "=", $itemtransaksi->No_Transaksi)->get();

                foreach ($transaksi as $item) {
                    $riwayatBayar = Fungsi::riwayatTransaksi($item->No_Transaksi);
                    $rb = [];

                    foreach ($riwayatBayar['bayartransaksi'] as $rbItem) {
                        $riwayat = [
                            "no_bayar" => $rbItem->no_bayar,
                            "tgl_input" => $rbItem->tgl_input,
                            "ket" => "Pembayaran dengan no invoice $rbItem->no_transaksi sebesar " . Fungsi::rupiah($rbItem->jumlah) . " via $rbItem->Keterangan."
                        ];
                        $rb[] = $riwayat;
                    }

                    $item = [
                        "no_transaksi" => $item->No_Transaksi,
                        "nama_pemesan" => $item->nama_pemesan,
                        "tgl_transaksi" => $item->Tanggal_Transaksi,
                        "status_bayar" => $item->Status_Bayar,
                        "status_transaksi" => $item->Status_Transaksi,
                        "total_qty" => (float)$item->total_qty,
                        "total_item" => (float)$item->total_item,
                        "grandtotal" => Fungsi::rupiahNoSymbol($item->net_total_sales),
                        "total_bayar" => Fungsi::rupiahNoSymbol($item->total_bayar),
                        "sisa_bayar" => Fungsi::rupiahNoSymbol($item->sisa_bayar),
                        "sisa_bayar_nonrp" => Fungsi::rupiahNoSymbol($item->sisa_bayar),
                        "nama_cs" => $item->nama_cs,
                        "riwayat_bayar" => (count($riwayatBayar['bayartransaksi']) == 0 ? null : $rb),
                        "keterangan" => $itemTr
                    ];

                    $items[] = $item;
                }
            }
        } else {
            foreach ($transaksi as $item) {
                $riwayatBayar = Fungsi::riwayatTransaksi($item->No_Transaksi);
                $rb = [];
                foreach ($riwayatBayar['bayartransaksi'] as $rbItem) {
                    $riwayat = [
                        "no_bayar" => $rbItem->no_bayar,
                        "tgl_input" => $rbItem->tgl_input,
                        "ket" => "Pembayaran dengan no invoice $rbItem->no_transaksi sebesar " . Fungsi::rupiah($rbItem->jumlah) . " via $rbItem->Keterangan."
                    ];
                    $rb[] = $riwayat;
                }

                $itemTransaksi = ItemTransaksi::where("No_Transaksi", "=", $item->No_Transaksi)->get();

                $itemTr = [];
                foreach ($itemTransaksi as $itemtransaksi) {
                    $transaksiItem = [
                        "nama_produk" => $itemtransaksi->Nama_Produk,
                        "keterangan" => $itemtransaksi->keterangan
                    ];

                    $itemTr[] = $transaksiItem;
                }

                $item = [
                    "no_transaksi" => $item->No_Transaksi,
                    "nama_pemesan" => $item->nama_pemesan,
                    "tgl_transaksi" => $item->Tanggal_Transaksi,
                    "status_bayar" => $item->Status_Bayar,
                    "status_transaksi" => $item->Status_Transaksi,
                    "total_qty" => (float)$item->total_qty,
                    "total_item" => (float)$item->total_item,
                    "grandtotal" => Fungsi::rupiahNoSymbol($item->net_total_sales),
                    "total_bayar" => Fungsi::rupiahNoSymbol($item->total_bayar),
                    "sisa_bayar" => Fungsi::rupiahNoSymbol($item->sisa_bayar),
                    "sisa_bayar_nonrp" => Fungsi::rupiahNoSymbol($item->sisa_bayar),
                    "nama_cs" => $item->nama_cs,
                    "riwayat_bayar" => (count($riwayatBayar['bayartransaksi']) == 0 ? null : $rb),
                    "keterangan" => $itemTr
                ];

                $items[] = $item;
            }
        }

        foreach ($kodekas as $kode) {
            $item = [
                "nama_kas" => $kode->nama
            ];
            $kasItem[] = $item;
        }

        return response()->json([
            'kodekas' => $kasItem,
            'transaksi' => ($transaksi->isEmpty() ? null : $items),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $sisaBayar = $request->total_sales - $request->total_bayar;
        $totalKembali = 0;
        $membership = "UMUM";
        if ($request->membership == 1) {
            $membership = "UMUM";
        } else if ($request->membership == 2) {
            $membership = "STUDIO";
        }
        Transaksi::create([
            "No_Transaksi" => $request->No_Transaksi,
            "Tanggal_Transaksi" => now(),
            "Status_Bayar" => $request->Status_Bayar,
            "Status_Transaksi" => $request->Status_Transaksi,
            "total_qty" => $request->total_qty,
            "total_item" => $request->total_item,
            "total_sales" => $request->total_sales,
            "biaya_lain" => $request->biaya_lain,
            "ongkir" => $request->ongkir,
            "potongan" => $request->diskon,
            "net_total_sales" => $request->total_sales,
            "total_bayar" => $request->total_bayar,
            "total_kembali" => $totalKembali,
            "sisa_bayar" => $sisaBayar,
            "hpp" => $request->hpp,
            "no_pemesan" => $membership,
            "nama_pemesan" => $request->nama_pemesan,
            "alamat_pemesan" => $request->alamat_pemesan,
            "telepon_pemesan" => Fungsi::hp($request->telepon_pemesan),
            "membership" => $membership,
            "nama_cs" => $name,
            "nama_kasir" => $name,
            "waktu_bayar" => now(),
            "tgl_jatuhtempo" => now(),
            "tgl_deadline" => now(),
            "Tgl_Modified" => now(),
            "ModifiedBy" => $name
        ]);

        return response()->json([
            'success' => true,
            'message' => "Transaksi dengan no invoice $request->No_Transaksi berhasil ditambahkan."
        ]);
    }

    public function store_item(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        ItemTransaksi::create([
            "No_Transaksi" => $request->No_Transaksi,
            "Tanggal_Transaksi" => now(),
            "Kode_Produk" => $request->Kode_Produk,
            "Nama_Produk" => $request->Nama_Produk,
            "p" => $request->p,
            "l" => $request->l,
            "Qty" => $request->Qty,
            "satuan" => $request->satuan,
            "cost" => $request->cost,
            "sales" => $request->sales,
            "subtotal_sales" => $request->subtotal_sales,
            "keterangan" => $request->keterangan,
            "isdimensi" => $request->isdimensi,
            "TglModified" => now(),
            "ModifiedBy" => $name
        ]);

        return response()->json([
            'success' => true,
            'message' => "Transaksi dengan no invoice $request->No_Transaksi berhasil ditambahkan."
        ]);
    }

    public function edit(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $sisaBayar = $request->total_sales - $request->total_bayar;
        $totalKembali = 0;
        if ($sisaBayar <= 0) {
            $statusBayar = "Lunas";
        } else {
            $statusBayar = "Belum Lunas";
        }
        Transaksi::where("No_Transaksi", "=", $request->No_Transaksi)->update([
            "Status_Bayar" => $statusBayar,
            "total_qty" => $request->total_qty,
            "total_item" => $request->total_item,
            "total_sales" => $request->total_sales,
            "biaya_lain" => $request->biaya_lain,
            "ongkir" => $request->ongkir,
            "potongan" => $request->diskon,
            "net_total_sales" => $request->total_sales,
            "total_bayar" => $request->total_bayar,
            "total_kembali" => $totalKembali,
            "sisa_bayar" => $sisaBayar,
            "hpp" => $request->hpp,
            "no_pemesan" => $request->no_pemesan,
            "nama_pemesan" => $request->nama_pemesan,
            "alamat_pemesan" => $request->alamat_pemesan,
            "telepon_pemesan" => $request->telepon_pemesan,
            "membership" => $request->membership,
            "Tgl_Modified" => now(),
            "ModifiedBy" => $name
        ]);
        ItemTransaksi::where("No_Transaksi", "=", $request->No_Transaksi)->delete();

        return response()->json([
            'success' => true,
            'message' => "Transaksi dengan no invoice $request->No_Transaksi berhasil diubah.",
            "status_bayar" => $statusBayar
        ]);
    }

    public function edit_item(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        ItemTransaksi::create([
            "No_Transaksi" => $request->No_Transaksi,
            "Tanggal_Transaksi" => now(),
            "Kode_Produk" => $request->Kode_Produk,
            "Nama_Produk" => $request->Nama_Produk,
            "p" => $request->p,
            "l" => $request->l,
            "Qty" => $request->Qty,
            "satuan" => $request->satuan,
            "cost" => $request->cost,
            "sales" => $request->sales,
            "subtotal_sales" => $request->subtotal_sales,
            "keterangan" => $request->keterangan,
            "isdimensi" => $request->isdimensi,
            "TglModified" => now(),
            "ModifiedBy" => $name
        ]);

        return response()->json([
            'success' => true,
            'message' => "Transaksi dengan no invoice $request->No_Transaksi berhasil diubah."
        ]);
    }

    public function hapus_transaksi(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $transaksi = Transaksi::where("No_Transaksi", "=", $request->noTransaksi);
        $totalBayar = $transaksi->first();
        $bayarTransaksi = BayarTransaksi::where("no_transaksi", "=", $request->noTransaksi);
        $noDokumen = $bayarTransaksi->get();
        $keterangan = "";
        if ($noDokumen->isEmpty()) {
            $keterangan = "Transaksi dengan no invoice $request->noTransaksi berhasil dihapus.";
        } else {
            foreach ($noDokumen as $bt) {
                Kas::where("Dokumen", "=", $bt->no_bayar)->update([
                    "Tanggal" => now(),
                    "Keterangan" => "Pembatalan Transaksi " . $request->noTransaksi,
                    "Dokumen" => "",
                    "Masuk" => 0,
                    "Keluar" => (float)$totalBayar->total_bayar,
                    "ModifiedBy" => $name
                ]);
                $bayarTransaksi->delete();
                $keterangan = "Transaksi dengan no invoice $request->noTransaksi berhasil dihapus. Data di kas diubah menjadi Pembatalan/Cashback.";
            }
        }
        ItemTransaksi::where("No_Transaksi", "=", $request->noTransaksi)->delete();
        $transaksi->delete();

        return response()->json([
            'success' => true,
            'message' => $keterangan
        ]);
    }

    public function status_transaksi(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        Transaksi::where("No_Transaksi", "=", $request->noTransaksi)->update([
            "Status_Transaksi" => $request->statTransaksi,
            "Tgl_Modified" => now(),
            "ModifiedBy" => $name
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status transaksi dengan no invoice $request->noTransaksi berhasil diupdate menjadi $request->statTransaksi."
        ]);
    }

    public function store_bayar(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $jenisBayar = Fungsi::switchJnsBayar($request->jnsBayar);
        $getNoBayar = BayarTransaksi::latest("no_bayar")->first();
        if ($getNoBayar === null) {
            $no_bayar = "BP00001";
        } else {
            $no_bayar = $getNoBayar->no_bayar;
            $no_bayar++;
        }

        $total_kembali = 0;
        if ($request->jumlah > 0) {
            $total_kembali = $request->jumlah - $request->total_bayar;
        }

        $getTransaksi = Transaksi::where("No_Transaksi", "=", $request->no_transaksi)->first();
        $total_bayar = $getTransaksi->total_bayar;
        $total_kembaliAwal = $getTransaksi->total_kembali;
        if ($total_bayar >= 0 || $total_kembali >= 0) {
            $total_bayar += $request->jumlah;
            $total_kembali += $total_kembaliAwal;
        }

        $status_bayar = "";
        if ($request->sisa == 0) {
            $status_bayar = "Lunas";
        } else {
            $status_bayar = "Belum Lunas";
        }

        $forKas = "";
        if ($getTransaksi->status_bayar == "Belum Lunas") {
            $forKas = "103";
        } else {
            $forKas = "400";
        }

        if ($total_kembali > 0) {
            $keluar = $total_kembali;
            $kembalian = $total_kembali;
        } else {
            $keluar = 0;
            $kembalian = 0;
        }

        BayarTransaksi::create([
            "no_bayar" => $no_bayar,
            "tgl_input" => now(),
            "no_transaksi" => $request->no_transaksi,
            "total_bayar" => $request->total_bayar,
            "jumlah" => $request->jumlah,
            "sisa" => $request->sisa,
            "modifiedby" => $name
        ]);

        Kas::create([
            "Tanggal" => now(),
            "kode_kas" => $request->jnsBayar,
            "Keterangan" => $jenisBayar,
            "Dokumen" => $no_bayar,
            "Masuk" => $request->jumlah,
            "Keluar" => $keluar,
            "ModifiedBy" => $name,
            "status" => 0,
            "forKas" => $forKas
        ]);

        Transaksi::where("No_Transaksi", "=", $request->no_transaksi)
            ->update([
                "total_bayar" => $total_bayar,
                "total_kembali" => $kembalian,
                "sisa_bayar" => $request->sisa,
                "Status_Bayar" => $status_bayar,
                "waktu_bayar" => now(),
                "Tgl_Modified" => now(),
                "ModifiedBy" => $name,
                "nama_kasir" => $name
            ]);

        return response()->json([
            'success' => true,
            'message' => "Pembayaran no invoice $request->no_transaksi berhasil dengan total bayar " . Fungsi::rupiah($request->jumlah) . " melalui $jenisBayar.",
            'status_bayar' => $status_bayar
        ]);
    }

    public function store_kas(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $kodeAkun = MasterAkun::where("kode", "=", $request->tujuanKas)->first();
        Kas::create([
            "Tanggal" => $request->Tanggal,
            "kode_kas" => $request->jnsBayar,
            "Keterangan" => $request->keterangan,
            "Dokumen" => "",
            "Masuk" => $request->masuk,
            "Keluar" => $request->keluar,
            "ModifiedBy" => $name,
            "status" => 0,
            "forKas" => $request->tujuanKas
        ]);

        if ($request->keterangan == "") {
            $keterangan = $kodeAkun->nama;
        } else {
            $keterangan = $request->keterangan;
        }

        return response()->json([
            'success' => true,
            'message' => "Kas dengan keterangan $keterangan berhasil ditambahkan."
        ]);
    }

    public function edit_kas(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $getKodeKas = Kas::where("Id_kas", $request->id);
        $dokumen = $getKodeKas->first();
        $kodeAkun = MasterAkun::where("kode", $request->tujuanKas)->first();
        if ($kodeAkun->kode == 400) {
            $keteranganKas = Fungsi::switchJnsBayar($request->jnsBayar);
        } else {
            $keteranganKas = $request->keterangan;
        }

        $keterangan = "Kas dengan keterangan $request->keterangan berhasil diubah.";

        $getKodeKas->update([
            "Tanggal" => $request->tgl,
            "kode_kas" => $request->jnsBayar,
            "Keterangan" => $keteranganKas,
            "Dokumen" => $dokumen->Dokumen,
            "Masuk" => $request->masuk,
            "Keluar" => $request->keluar,
            "ModifiedBy" => $name,
            "status" => 0,
            "forKas" => $request->tujuanKas
        ]);

        if ($request->keterangan == "") {
            $keterangan = "Kas dengan keterangan $kodeAkun->nama berhasil diubah.";
        }

        if ($request->tujuanKas == 400) {
            $BayarTransaksi = BayarTransaksi::where("no_bayar", $dokumen->Dokumen);
            $BT = $BayarTransaksi->first();
            $Transaksi = Transaksi::where("No_Transaksi", $BT->no_transaksi);
            $transaksi = $Transaksi->first();
            $totalBayarEdit = 0;
            $BayarTransaksi->update([
                "jumlah" => $request->masuk
            ]);

            $BTFull = BayarTransaksi::where("no_transaksi", "=", $BT->no_transaksi)->get();
            foreach ($BTFull as $bt) {
                $totalBayarEdit += (float)$bt->jumlah;
            }

            if ($totalBayarEdit > (float)$transaksi->net_total_sales) {
                $statusBayar = "Lunas";
            } else {
                $statusBayar = "Belum Lunas";
            }

            $sisaBayar = $totalBayarEdit - (float)$transaksi->net_total_sales;
            $kembalian = $totalBayarEdit - (float)$transaksi->net_total_sales;
            if ($sisaBayar < 0) {
                $sisaBayar = str_replace("-", "", $sisaBayar);
            } else {
                $sisaBayar = 0;
            }

            if ($kembalian > 0) {
                $getKodeKas->update([
                    "Keluar" => $kembalian
                ]);
            } else {
                $kembalian = 0;
                $getKodeKas->update([
                    "Keluar" => $kembalian
                ]);
            }

            $Transaksi->update([
                "Status_Bayar" => $statusBayar,
                "total_bayar" => $totalBayarEdit,
                "total_kembali" => $kembalian,
                "sisa_bayar" => $sisaBayar,
                "Tgl_Modified" => now(),
                "ModifiedBy" => $name,
                "nama_kasir" => $name
            ]);

            $keterangan = "Kas dengan no invoice $BT->no_transaksi berhasil diubah.";
        }

        return response()->json([
            'success' => true,
            'message' => $keterangan
        ]);
    }

    public function hapus_kas(Request $request)
    {
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $name = $usersData->nama_belakang_karyawan !== null || $usersData->nama_belakang_karyawan !== '' ? $usersData->nama_depan_karyawan . " " . $usersData->nama_belakang_karyawan : $usersData->nama_depan_karyawan;
        $getKodeKas = Kas::where("Id_kas", "=", $request->id);
        $kodeKas = $getKodeKas->first();
        $kodeAkun = MasterAkun::where("kode", "=", $kodeKas->forKas)->first();
        $BayarTransaksi = BayarTransaksi::where("no_bayar", "=", $kodeKas->Dokumen);
        $BT = $BayarTransaksi->first();

        if ($kodeKas->Keterangan == "" || $kodeKas->Dokumen == "") {
            $keterangan = "Kas dengan keterangan $kodeAkun->nama berhasil dihapus.";
        } else {
            if ($kodeKas->forKas == 400) {
                $Transaksi = Transaksi::where("No_Transaksi", "=", $BT->no_transaksi);
                $transaksi = $Transaksi->first();

                $bayaranBaru = (float)$BT->jumlah - (float)$transaksi->total_bayar;

                if ($bayaranBaru < (float)$transaksi->net_total_sales) {
                    $statusBayar = "Belum Lunas";
                } else {
                    $statusBayar = "Lunas";
                }

                if ($bayaranBaru <= 0) {
                    $totalBayar = str_replace("-", "", $bayaranBaru);
                    $sisaBayar = (float)$BT->jumlah + (float)$transaksi->sisa_bayar;
                    $kembalian = 0;
                } else {
                    $totalBayar = $bayaranBaru;
                    $sisaBayar = 0;
                    $kembalian = $bayaranBaru - (float)$transaksi->net_total_sales;
                    if ($kembalian < 0) {
                        $kembalian = 0;
                    } else {
                        $kembalian = $kembalian;
                    }
                }

                if ($kembalian > 0) {
                    $getKodeKas->update([
                        "Keluar" => $kembalian
                    ]);
                } else {
                    $getKodeKas->update([
                        "Keluar" => $kembalian
                    ]);
                }

                $Transaksi->update([
                    "Status_Bayar" => $statusBayar,
                    "total_bayar" => $totalBayar,
                    "total_kembali" => $kembalian,
                    "sisa_bayar" => $sisaBayar,
                    "Tgl_Modified" => now(),
                    "ModifiedBy" => $name,
                    "nama_kasir" => $name
                ]);
                $BayarTransaksi->delete();
                $keterangan = "Kas dengan no invoice $BT->no_transaksi berhasil dihapus.";
            } else {
                $keterangan = "Kas dengan keterangan $kodeKas->Keterangan berhasil dihapus.";
            }
        }

        Kas::where("Id_kas", "=", $request->id)->delete();

        return response()->json([
            'success' => true,
            'message' => $keterangan
        ]);
    }

    public function view_lap_keuangan($tglawal, $tglakhir)
    {
        $periode = "$tglawal - $tglakhir";
        $bulanIni = Transaksi::whereMonth("Tanggal_Transaksi", date("m", strtotime($tglawal)))->whereYear("Tanggal_Transaksi", date("Y", strtotime($tglawal)))->sum('total_bayar');
        $bulanLalu = Transaksi::whereMonth("Tanggal_Transaksi", date("m", strtotime($tglawal)) - 1)->whereYear("Tanggal_Transaksi", date("Y", strtotime($tglawal)))->sum('total_bayar');
        $penjualan = Transaksi::whereRaw("DATE(Tanggal_Transaksi) BETWEEN '$tglawal' AND '$tglakhir'")->sum('total_bayar');
        $kasPenjualan = Transaksi::whereRaw("DATE(Tanggal_Transaksi) BETWEEN '$tglawal' AND '$tglakhir'")->sum('total_bayar');
        $kasMasuk = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->sum('Masuk');
        $kasKeluar = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->where('Masuk', '=', 0)->sum('Keluar');
        $kasMasukBulanan = KAS::whereMonth("Tanggal", date("m", strtotime($tglawal)))->whereYear("Tanggal", date("Y", strtotime($tglawal)))->sum('Masuk');
        $kasKeluarBulanan = KAS::whereMonth("Tanggal", date("m", strtotime($tglawal)))->whereYear("Tanggal", date("Y", strtotime($tglawal)))->where('Masuk', '=', 0)->sum('Keluar');
        $pinjaman = DB::table('kas')->join('master_akuns', "master_akuns.kode", "=", "kas.forKas")->whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->where('kas.forKas', "=", 621)->sum('kas.Keluar');
        $getPengeluaranSelf = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->where("Masuk", "=", 0)->where("forKas", "<>", 623)->get();
        $getPengeluaranCbg = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->where("Masuk", "=", 0)->where("forKas", "=", 623)->get();
        $getEdc = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->where("kode_kas", "=", "BSI")->get();
        $totalEdc = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->where("kode_kas", "=", "BSI")->sum('Masuk');
        $totalEdcBulanan = KAS::whereMonth("Tanggal", date("m", strtotime($tglawal)))->whereYear("Tanggal", date("Y", strtotime($tglawal)))->where("kode_kas", "=", "BSI")->sum('Masuk');
        $piutang = Transaksi::whereMonth("Tanggal_Transaksi", date("m", strtotime($tglawal)))->whereYear("Tanggal_Transaksi", date("Y", strtotime($tglawal)))->sum('sisa_bayar');
        $totalHPP = Transaksi::whereRaw("DATE(Tanggal_Transaksi) BETWEEN '$tglawal' AND '$tglakhir'")->sum('hpp');
        $totalHPPBulanan = Transaksi::whereMonth("Tanggal_Transaksi", date("m", strtotime($tglawal)))->whereYear("Tanggal_Transaksi", date("Y", strtotime($tglawal)))->sum('hpp');
        $pelunasan = $kasMasuk - $kasPenjualan;
        $profitHarian = $kasPenjualan - $totalHPP;
        $profitBulanan = $bulanIni - $totalHPPBulanan;
        $setorBankHarian = $kasMasuk - $kasKeluar - $totalEdc;
        $setorBankBerjalan = $kasMasukBulanan - $kasKeluarBulanan - $totalEdcBulanan;
        $pengeluaranSelf = [];
        $pengeluaranCbg = [];
        $edc = [];

        foreach ($getPengeluaranSelf as $item) {
            $akuns = MasterAkun::where('kode', '=', $item->forKas)->first();
            $keterangan = $item->Keterangan;
            if ($keterangan == null || $keterangan == "") {
                $keterangan = $akuns->nama;
            }

            $item = [
                "keterangan" => ucfirst(strtolower($keterangan)),
                "subtotal" => Fungsi::rupiah($item->Keluar)
            ];

            $pengeluaranSelf[] = $item;
        }

        foreach ($getPengeluaranCbg as $item) {
            $item = [
                "keterangan" => ucfirst($item->Keterangan),
                "subtotal" => Fungsi::rupiah($item->Keluar)
            ];

            $pengeluaranCbg[] = $item;
        }

        foreach ($getEdc as $item) {
            $keterangan = null;
            if($item->Dokumen !== ''){
                $BT = BayarTransaksi::where("no_bayar", "=", $item->Dokumen)->first();
                $transaksi = Transaksi::where("No_Transaksi", "=", $BT->no_transaksi)->first();
                $keterangan = strtoupper($transaksi->No_Transaksi . "_" . $transaksi->nama_pemesan);
            }
            
            $item = [
                "keterangan" => $keterangan,
                "subtotal" => Fungsi::rupiah($item->Masuk)
            ];

            $edc[] = $item;
        }

        return response()->json([
            'periode' => $periode,
            'omset_bulan_ini' => Fungsi::rupiah($bulanIni),
            'omset_bulan_lalu' => Fungsi::rupiah($bulanLalu),
            'pemasukan' => [
                'omset_hari_ini' => Fungsi::rupiah($kasMasuk),
                'pelunasan_piutang' => Fungsi::rupiah($pelunasan)
            ],
            'pengeluaran' => [
                'biaya' => Fungsi::rupiah($kasKeluar),
                'pinjaman' => Fungsi::rupiah($pinjaman),
                'item_self' => ($getPengeluaranSelf->isEmpty() ? "-" : $pengeluaranSelf),
                'item_cabang' => ($getPengeluaranCbg->isEmpty() ? "-" : $pengeluaranCbg)
            ],
            'profit_harian' => Fungsi::rupiah($profitHarian),
            'profit_bulanan' => Fungsi::rupiah($profitBulanan),
            'piutang_bulan_ini' => Fungsi::rupiah($piutang),
            'edc' => [
                'total' => Fungsi::rupiah($totalEdc),
                'item' => ($getEdc->isEmpty() ? "-" : $edc)
            ],
            'edc_berjalan' => Fungsi::rupiah($totalEdcBulanan),
            'setor_bank' => Fungsi::rupiah($setorBankHarian),
            'setor_bank_berjalan' => Fungsi::rupiah($setorBankBerjalan)
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function view_lap_penjualan($tglawal, $tglakhir)
    {
        $periode = $tglawal . " - " . $tglakhir;
        $getPenjualan = Transaksi::whereRaw("DATE(Tanggal_Transaksi) BETWEEN DATE('$tglawal') AND DATE('$tglakhir')")->orderBy('Tanggal_Transaksi', 'ASC')->get();
        $penjualan = [];
        if ($getPenjualan->isEmpty()) {
            $penjualan = null;
        }
        foreach ($getPenjualan as $transaksi) {
            $tgl = date("d", strtotime($transaksi->Tanggal_Transaksi)) . Fungsi::convertBulanShort(date("M", strtotime($transaksi->Tanggal_Transaksi))) . date("Y", strtotime($transaksi->Tanggal_Transaksi));

            $nama_produk = "";
            $getItemTransaksi = ItemTransaksi::where("No_Transaksi", "=", $transaksi->No_Transaksi)->get();
            foreach ($getItemTransaksi as $item) {
                $nama_produk = $item->Nama_Produk;
                if ($item->isdimensi == 1) {
                    $nama_produk .= " UK. " . (float)$item->p . "x" . (float)$item->l . $item->satuan;
                }
            }

            $item = [
                "no_transaksi" => $transaksi->No_Transaksi,
                "tanggal" => $tgl,
                "pemesan" => ucfirst($transaksi->nama_pemesan),
                "nama_produk" => $nama_produk,
                "qty" => (float)$transaksi->total_qty,
                "total_item" => (float)$transaksi->total_item,
                "membership" => $transaksi->membership,
                "cs" => strtoupper($transaksi->nama_cs),
                "subtotal" => Fungsi::rupiah($transaksi->net_total_sales)
            ];

            $penjualan[] = $item;
        }

        return response()->json([
            'periode' => $periode,
            'penjualan' => $penjualan
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function view_lap_kas($tglawal, $tglakhir)
    {
        $periode = $tglawal . " - " . $tglakhir;
        $getArusKas = KAS::whereRaw("DATE(Tanggal) BETWEEN '$tglawal' AND '$tglakhir'")->get();
        $i = 0;
        $kas = [];

        foreach ($getArusKas as $item) {
            $i++;
            if (!empty($item->Dokumen)) {
                $BT = BayarTransaksi::where("no_bayar", "=", $item->Dokumen)->get();
                foreach ($BT as $bt) {
                    $BTNoTransaksi = $bt->no_transaksi;
                }
                $transaksi = Transaksi::where("No_Transaksi", "=", $BTNoTransaksi)->first();
                $no_transaksi = $transaksi->No_Transaksi;
                $nama_pemesan = ucfirst($transaksi->nama_pemesan);
            } else {
                $no_transaksi = "-";
                $nama_pemesan = "-";
            }

            $item = [
                "no" => $i,
                "tanggal" => $item->Tanggal,
                "kas" => $item->kode_kas,
                "no_transaksi" => $no_transaksi,
                "nama_pemesan" => $nama_pemesan,
                "keterangan" => ucfirst($item->Keterangan),
                "masuk" => Fungsi::rupiah($item->Masuk),
                "keluar" => Fungsi::rupiah($item->Keluar),
                "input_by" => strtoupper($item->ModifiedBy)
            ];

            $kas[] = $item;
        }

        return response()->json([
            'periode' => $periode,
            'kas' => ($getArusKas->isEmpty() ? "-" : $kas)
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function lihat_laporan($cabang, $laporan, $format, $tglawal, $tglakhir)
    {
        function periode($tgl)
        {
            $tanggal = date("d", strtotime($tgl));
            $bulan = Fungsi::convertBulanShort(date("M", strtotime($tgl)));
            $tahun = date("Y", strtotime($tgl));

            return $tanggal . " " . $bulan . " " . $tahun;
        }

        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $namaCabang = $dataPerusahaan['data'][0]['nama_cabang'];
        $iconCabang = $dataPerusahaan['data'][0]['icon'];
        $alamatCabang = $dataPerusahaan['data'][0]['alamat_cabang'];
        $periode = periode($tglawal) . " - " . periode($tglakhir);

        if (strtolower($laporan) == "penjualan") {
            $data = self::view_lap_penjualan($tglawal, $tglakhir);
        } else if (strtolower($laporan) == "kas") {
            $data = self::view_lap_kas($tglawal, $tglakhir);
        } else if (strtolower($laporan) == "keuangan") {
            $data = self::view_lap_keuangan($tglawal, $tglakhir);
        }

        return view('report.laporan', [
            "title" => "Laporan " . ucfirst($laporan) . " " . periode(date("Y-m-d")),
            "cabang" => $namaCabang,
            "icon_cabang" => $iconCabang,
            "alamat_cabang" => $alamatCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "for" => strtolower($laporan),
            "periode" => $periode,
            "laporan" => $data
        ]);
    }

    public function download_laporan($cabang, $laporan, $format, $tglawal, $tglakhir)
    {
        function period($tgl)
        {
            $tanggal = date("d", strtotime($tgl));
            $bulan = Fungsi::convertBulanShort(date("M", strtotime($tgl)));
            $tahun = date("Y", strtotime($tgl));

            return $tanggal . " " . $bulan . " " . $tahun;
        }

        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang);
        $namaCabang = $dataPerusahaan['data'][0]['nama_cabang'];
        $alamatCabang = $dataPerusahaan['data'][0]['alamat_cabang'];
        $periode = period($tglawal) . " - " . period($tglakhir);

        if (strtolower($laporan) == "penjualan") {
            $data = self::view_lap_penjualan($tglawal, $tglakhir);
        } else if (strtolower($laporan) == "kas") {
            $data = self::view_lap_kas($tglawal, $tglakhir);
        } else if (strtolower($laporan) == "keuangan") {
            $data = self::view_lap_keuangan($tglawal, $tglakhir);
        }

        $pdf = PDF::loadView('report.laporan', [
            "title" => "Laporan " . ucfirst($laporan) . " " . period(date("Y-m-d")),
            "cabang" => $namaCabang,
            "icon_cabang" => '',
            "alamat_cabang" => $alamatCabang,
            "nama_user" => $usersData['nama_user'],
            "foto_user" => $usersData['foto'],
            "for" => strtolower($laporan),
            "periode" => $periode,
            "laporan" => $data
        ]);
        $fileName = "Laporan_" . ucfirst($laporan) . "_" . date("Y_m_d") . "." . $format;
        return $pdf->download($fileName);
    }

    public function get_item(Request $request)
    {
        $member = strtoupper($request->member);
        $data = MasterItem::orderBy('Nama_Produk', 'ASC')->get();
        $items = [];

        foreach ($data as $item) {
            if ($member == "UMUM" || $member == "STUDIO") {
                $produk = ProdukHarga::where(['Kode_Produk' => $item->Kode_Produk, 'jenis_harga' => $member])->orderBy('min_pembelian', 'ASC')->get();
                $harga = [];
                foreach ($produk as $itemproduk) {
                    $harga[] = [
                        "min" => $itemproduk->min_pembelian,
                        "harga" => $itemproduk->harga
                    ];
                    
                    $itemp = [
                        "kodeproduk" => $item->Kode_Produk,
                        "namaproduk" => $item->Nama_Produk,
                        "kategori" => $item->Kategori,
                        "satuan" => $item->Satuan,
                        "isdimensi" => $item->isdimensi,
                        "hpp" => $item->harga_beli,
                        "harga" => $harga
                    ];
                }
            } else {
                $produk = ProdukHarga::where('Kode_Produk', "=", $item->Kode_Produk)->get();
                $itemp = $produk;
            }

            $items[] = $itemp;
        }

        return response()->json([
            'item' => ($data->isEmpty() ? "-" : $items)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    
    public function search_produk($namaproduk, $member)
    {
        $namaproduk = strtolower($namaproduk);
        $member = strtoupper($member);
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = $dataPerusahaan->Nama_Perusahaan;
        if($namaproduk == "all"){
            $data = MasterItem::orderBy('Nama_Produk', 'asc')->get();
        } else {
            $data = MasterItem::where("Nama_Produk", "LIKE", "%$namaproduk%")->orderBy('Nama_Produk', 'asc')->get();
        }
        
        $items = [];

        foreach ($data as $item) {
            if ($member == "UMUM" || $member == "STUDIO") {
                $produk = ProdukHarga::where(['Kode_Produk' => $item->Kode_Produk, 'jenis_harga' => $member])->orderBy('min_pembelian', 'ASC')->get();
                $harga = [];

                foreach ($produk as $itemproduk) {
                    $harga[] = [
                        "min" => $itemproduk->min_pembelian,
                        "harga" => $itemproduk->harga
                    ];
                    
                    $itemp = [
                        "kodeproduk" => $item->Kode_Produk,
                        "namaproduk" => $item->Nama_Produk,
                        "kategori" => $item->Kategori,
                        "satuan" => $item->Satuan,
                        "isdimensi" => $item->isdimensi,
                        "hpp" => $item->harga_beli,
                        "harga" => $harga
                    ];
                }
            } else {
                $produk = ProdukHarga::where('Kode_Produk', "=", $item->Kode_Produk)->get();
                $itemp = $produk;
            }

            $items[] = $itemp;
        }

        return response()->json([
            'item' => ($data->isEmpty() ? "-" : $items)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    
    public function kategori_produk()
    {
        $kategori = MasterItem::select('Kategori')->distinct()->orderBy('Kategori', 'ASC')->get();
        $items = [];
        foreach($kategori as $item){
            $item = [
                "nama" => $item->Kategori
            ];
            
            $items[] = $item;
        }
        
        return response()->json([
            'kategori' => ($kategori->isEmpty() ? null : $items)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    
    public function satuan_produk()
    {
        $satuan = MasterItem::select('Satuan')->distinct()->orderBy('Satuan', 'ASC')->get();
        $items = [];
        foreach($satuan as $item){
            $item = [
                "nama" => $item->Satuan
            ];
            
            $items[] = $item;
        }
        
        return response()->json([
            'satuan' => ($satuan->isEmpty() ? null : $items)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    
    public function edit_produk(Request $request)
    {
        $produk = MasterItem::where('Kode_Produk', '=', $request->kp)->first();
        $namaProduk = ucfirst(strtolower($produk->Nama_Produk));
        $dataTitle = ucfirst($request->forData);
        if($request->forData == "studio" || $request->forData == "umum"){
            $dataTitle = "Harga " . ucfirst($request->forData);
        } else if($request->forData == "namaproduk"){
            $dataTitle = "Nama produk untuk";
        }
        $usersData = Fungsi::getUsersDataFromApp(Cookie::get("username"));
        $nama = $usersData['nama_user'];
        $dataPerusahaan = perusahaan::first();
        $shortCabang = $dataPerusahaan->Short_Cabang;
        
        if(strtolower($request->forData) == 'kategori'){
            MasterItem::where('Kode_Produk', '=', $request->kp)
            ->update([
                "Kategori" => $request->value,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);   
        } else if(strtolower($request->forData) == 'satuan'){
            $isdimensi = (in_array(strtoupper($request->value), ["CM", "METER", "M²"]) ? 1 : 0);
            MasterItem::where('Kode_Produk', '=', $request->kp)
            ->update([
                "Satuan" => $request->value,
                "isdimensi" => $isdimensi,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);
        } else if(strtolower($request->forData) == 'minpembelian'){
            ProdukHarga::where('Kode_Produk', '=', $request->kp)
            ->update([
                "min_pembelian" => $request->value,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);
        } else if(strtolower($request->forData) == 'umum'){
            ProdukHarga::where(['Kode_Produk' => $request->kp, 'jenis_harga' => strtoupper($request->forData)])
            ->update([
                "harga" => $request->value,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);
        } else if(strtolower($request->forData) == 'studio'){
            ProdukHarga::where(['Kode_Produk' => $request->kp, 'jenis_harga' => strtoupper($request->forData)])
            ->update([
                "harga" => $request->value,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);
        } else if(strtolower($request->forData) == 'namaproduk'){
            $explodeNP = explode(" ", $request->value);
            $newKodeProduk = "";
            foreach($explodeNP as $np){
                $newKodeProduk .= strtoupper(substr($np, 0, 1));
            }
            $replaceSymbols = preg_replace('/[^A-Za-z0-9\-]/', '', $newKodeProduk);
            $kp = preg_replace('/-+/', '-', $replaceSymbols);
            $newKodeProduk = $shortCabang . $kp . $produk->id;
            MasterItem::where('Kode_Produk', '=', $request->kp)
            ->update([
                "Kode_Produk" => $newKodeProduk,
                "Nama_produk" => $request->value,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);
            
            ProdukHarga::where('Kode_Produk', '=', $request->kp)
            ->update([
                "Kode_Produk" => $newKodeProduk,
                "ModifiedBY" => $nama,
                "Tgl_Modified" => now()
            ]);
        }
        
        return response()->json([
            "success" => true,
            "message" => "$dataTitle produk $namaProduk berhasil diubah."
        ]);
    }

    public function get_customer(Request $request)
    {
        $name = $request->qname;
        $cabang = request()->segments();
        $userid = Auth::user()->id;
        $usersData = User::where('id', $userid)->first();
        $dataPerusahaan = UserController::getBranch('qsearch=' . $cabang[0]);
        $shortCabang = strtolower($dataPerusahaan['data'][0]['folder']);
        if ($name !== "") {
            $name = "%$name%";
        } else {
            $name = "";
        }

        $data = Transaksi::selectRaw('DISTINCT nama_pemesan, telepon_pemesan, alamat_pemesan, membership')->where('nama_pemesan', 'LIKE', "%$name%")->get();
        $items = [];

        foreach ($data as $item) {
            $item = [
                "nama_pemesan" => ucfirst(strtolower($item->nama_pemesan)),
                "telp_pemesan" => $item->telepon_pemesan,
                "alamat_pemesan" => $item->alamat_pemesan,
                "membership" => $item->membership
            ];

            $items[] = $item;
        }

        return response()->json([
            'customers' => ($data->isEmpty() ? "-" : $items)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    
    public function getContent($id, $geturl = false)
    {
        $url = "https://api2.musical.ly/aweme/v1/aweme/detail/?aweme_id=" . $id;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Mobile Safari/537.36',
            CURLOPT_ENCODING        => "utf-8",
            CURLOPT_AUTOREFERER     => false,
            CURLOPT_COOKIEJAR       => 'cookie.txt',
            CURLOPT_COOKIEFILE      => 'cookie.txt',
            CURLOPT_REFERER         => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_MAXREDIRS       => 10,
        );
        curl_setopt_array( $ch, $options );
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
          curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($geturl === true)
        {
            return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }
        curl_close($ch);
        return strval($data);
    }
    
    public function unduhlah_api($domainFrom, $id)
    {
        $response = self::getContent($id);
        $resp = json_decode($response, true);
        return $resp;
    }

    public function __invoke()
    {
        //
    }
}
