<?php
$fungsi = new App\Models\Fungsi;

$hari = date("d", strtotime($transaksi->Tanggal_Transaksi));
$namaHari = $fungsi->convertNamaHari(date("l", strtotime($transaksi->Tanggal_Transaksi)));
$bulanShort = $fungsi->convertBulanShort(date("M", strtotime($transaksi->Tanggal_Transaksi)));
$bulanLong = $fungsi->convertBulanLong(date("F", strtotime($transaksi->Tanggal_Transaksi)));
$tahun = date("Y", strtotime($transaksi->Tanggal_Transaksi));
$waktu = date("H:i", strtotime($transaksi->Tanggal_Transaksi));
$tanggal_pemesanan = $hari . " " . $bulanShort . " " . $tahun . " " . $waktu . " WIB";

$hariBayar = date("d", strtotime($transaksi->waktu_bayar));
$namaHariBayar = $fungsi->convertNamaHari(date("l", strtotime($transaksi->waktu_bayar)));
$bulanShortBayar = $fungsi->convertBulanShort(date("M", strtotime($transaksi->waktu_bayar)));
$bulanLongBayar = $fungsi->convertBulanLong(date("F", strtotime($transaksi->waktu_bayar)));
$tahunBayar = date("Y", strtotime($transaksi->waktu_bayar));
$waktuBayar = date("H:i", strtotime($transaksi->waktu_bayar));
$tanggal_bayar = $namaHariBayar . ", " . $hariBayar . " " . date("F", strtotime($transaksi->waktu_bayar)) . " " . $tahunBayar . " " . $waktuBayar;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial;
        }

        .container {
            width: 256px;
        }

        .flex-column {
            display: flex;
            flex-direction: column;
        }
        
        .flex-row {
            display: flex;
            flex-direction: row;
        }

        .align-center{
            align-items: center;
        }

        hr {
            border-color:#000000;
            border-style:solid;
            border-width:0px;
            border-top-width:1px;
        }

        .logo-icon {
            width:100%;
            height:47px;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-8pt-bold {
            font-size: 8pt;
            color: #000000;
            font-weight: bold;
        }

        .text-8pt-bolder {
            font-size: 8pt;
            color: #000000;
            font-weight: bolder;
        }

        .text-8pt-normal {
            font-size: 8pt;
            color: #000000;
            font-weight: normal;
        }
        
        .text-9pt-bold {
            font-size: 9pt;
            color: #000000;
            font-weight: bold;
        }

        .text-9pt-bolder {
            font-size: 9pt;
            color: #000000;
            font-weight: bolder;
        }

        .text-9pt-normal {
            font-size: 9pt;
            color: #000000;
            font-weight: normal;
        }

        .baris {
            display: flex;
            flex-direction: row;
        }

        .baris-kolom-2 {
            position: absolute;
            left: 110px;
        }

        .baris-kolom-3 {
            position: absolute;
            left: 120px;
        }

        .mt-1 {
            margin-top: 1em;
        }

        .mb-1 {
            margin-bottom: 1em;
        }

        .mt-05 {
            margin-top: .5em;
        }

        .mb-05 {
            margin-bottom: .5em;
        }

        .mr-1 {
            margin-right: 1em;
        }

        .ml-1 {
            margin-left: 1em;
        }

        .mr-3px {
            margin-right: 3px;
        }

        .mr-5px {
            margin-right: 5px;
        }

        .ml-5px {
            margin-left: 5px;
        }

        .mr-auto {
            margin-right: auto;
        }

        .ml-auto {
            margin-left: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="head-container">
            <div class="logo-icon">{!! $perusahaan['logo_svg'] !!}</div>
            <div class="flex-column">
                <div class="information mt-1 mb-1">
                    <div class="text-center text-uppercase text-9pt-bolder">{{  $perusahaan['nama_cabang'] }}</div>
                    <div class="text-center text-8pt-normal">{{  $perusahaan['alamat_cabang'] }}</div>
                    <div class="text-center text-8pt-normal">WA : <b>{{  $perusahaan['telpon_cabang'] }}</b></div>
                    <div class="text-center text-8pt-normal">{{  $perusahaan['email_cabang'] }}</div>
                </div>
                <div class="flex-column">
                    <div class="flex-row">
                        <div class="baris-kolom-1 text-9pt-normal">Nomor Invoice</div>
                        <div class="baris-kolom-2 text-9pt-normal">:</div>
                        <div class="baris-kolom-3 text-9pt-bold">{{ $transaksi->No_Transaksi }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="baris-kolom-1 text-9pt-normal">Tanggal Order</div>
                        <div class="baris-kolom-2 text-9pt-normal">:</div>
                        <div class="baris-kolom-3 text-9pt-normal">{{ $tanggal_pemesanan }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="baris-kolom-1 text-9pt-normal">Nama Pemesan</div>
                        <div class="baris-kolom-2 text-9pt-normal">:</div>
                        <div class="baris-kolom-3 text-9pt-bold">{{ $transaksi->nama_pemesan }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="baris-kolom-1 text-9pt-normal">HP/WA</div>
                        <div class="baris-kolom-2 text-9pt-normal">:</div>
                        <div class="baris-kolom-3 text-9pt-normal">{{ $transaksi->telepon_pemesan }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="baris-kolom-1 text-9pt-normal">Nama CS</div>
                        <div class="baris-kolom-2 text-9pt-normal">:</div>
                        <div class="baris-kolom-3 text-9pt-normal">{{ $transaksi->nama_cs }}</div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="body-container">
            <?php 
                $no = 1;   
            ?>
            @foreach($getTransaksi as $item)
                <div class="itemproduk mb-05">
                    <div class="flex-row">
                        <div class="text-9pt-normal">{{ $no++ . "." }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="mr-5px text-9pt-normal">Nama Produk</div>
                        <div class="mr-3px text-9pt-normal"> : </div>
                        <div class="text-9pt-normal">{{ $item->Nama_Produk }}</div>
                    </div>
                    @if((float)$item->p > 0 || (float)$item->l > 0)
                        <div class="flex-row">
                            <div class="mr-5px text-9pt-normal">Ukuran</div>
                            <div class="mr-3px text-9pt-normal"> : </div>
                            <div class="text-9pt-normal">{{ (float)$item->p. "x" . (float)$item->l . " " . $item->satuan }}</div>
                        </div>
                    @endif
                    <div class="flex-row">
                        <div class="mr-5px text-9pt-normal">Jumlah</div>
                        <div class="mr-3px text-9pt-normal">:</div>
                        <div class="text-9pt-normal">{{ $item->Qty }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="mr-5px text-9pt-normal">Harga Per item</div>
                        <div class="mr-3px text-9pt-normal">:</div>
                        <div class="text-9pt-normal">{{ $fungsi->rupiah($item->sales) }}/{{ $item->satuan }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="mr-5px text-9pt-normal">Subtotal Harga</div>
                        <div class="mr-3px text-9pt-normal">:</div>
                        <div class="text-9pt-normal">{{ $fungsi->rupiah($item->subtotal_sales) }}</div>
                    </div>
                    <div class="flex-row">
                        <div class="mr-5px text-9pt-normal">Ket Produk</div>
                        <div class="mr-3px text-9pt-normal">:</div>
                        <div class="text-9pt-normal">{{ $item->keterangan }}</div>
                    </div>
                </div>
            @endforeach
            <hr>
            <div class="totalitem">
                <div class="flex-row">
                    <div class="mr-5px text-9pt-normal">Total Item</div>
                    <div class="mr-1 text-9pt-normal">:</div>
                    <div class="text-9pt-normal">{{ (float)$transaksi->total_item }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-5px text-9pt-normal">Total QTY</div>
                    <div class="mr-1 text-9pt-normal">:</div>
                    <div class="text-9pt-normal">{{ (float)$transaksi->total_qty }}</div>
                </div>
            </div>
            <hr>
            <div class="subbody-container">
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">SUBTOTAL</div>
                    <div class="text-9pt-normal">{{ $fungsi->rupiah($transaksi->total_sales) }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">ONGKIR</div>
                    <div class="text-9pt-normal">{{ $fungsi->rupiah($transaksi->ongkir) }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">GRANDTOTAL</div>
                    <div class="text-9pt-normal">{{ $fungsi->rupiah($transaksi->net_total_sales) }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">TOTAL BAYAR</div>
                    <div class="text-9pt-normal">{{ $fungsi->rupiah($transaksi->total_bayar) }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">SISA</div>
                    <div class="text-9pt-normal">{{ $fungsi->rupiah($transaksi->sisa_bayar) }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">KEMBALIAN</div>
                    <div class="text-9pt-normal">{{ $fungsi->rupiah($transaksi->kembalian) }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-auto text-9pt-normal">STATUS BAYAR</div>
                    <div class="text-9pt-bold">{{ $transaksi->Status_Bayar }}</div>
                </div>
            </div>
        </div>
        <hr>
        <div class="foot-container">
            <div class="subfoot-container">
                <div class="flex-row">
                    <div class="mr-5px text-9pt-normal">Kasir</div>
                    <div class="mr-1 text-9pt-normal">:</div>
                    <div class="text-9pt-normal">{{ $transaksi->nama_kasir }}</div>
                </div>
                <div class="flex-row">
                    <div class="mr-5px text-9pt-normal">Tgl Bayar</div>
                    <div class="mr-1 text-9pt-normal">:</div>
                    <div class="text-9pt-normal">{{ $tanggal_bayar }}</div>
                </div>
            </div>
            <hr>
            <div class="footer-information">
                <div class="mb-1 text-center text-uppercase text-8pt-normal">{!! $perusahaan['catatan'] !!}</div>
                <div class="qrcode-invoice text-center mb-05">
                    {!! $qrcode !!}
                </div>
                <div class="text-center text-8pt-bold text-uppercase">Scan QR CODE Diatas Untuk Cek Status Pemesanan Anda Secara Online</div>
            </div>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>