<?php
$laporan = json_encode($laporan);
$laporan = json_decode($laporan, true);
$laporan = $laporan['original'];
if($for !== "keuangan"){
    $laporan = $laporan[(string)$for];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="https://app.alkaysan.com/assets/img/icon-alkaysan.png">
    <title>{{ $title }}</title>
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <style>
        @page{
            margin: auto;
        }

        body, h5, h3, h4, span {
            color: #000000;
        }

        .sidebar-brand-icon svg g {
            fill: var(--company-color-primary);
        }

        .table thead th {
            vertical-align: middle;
        }

        .table td, .table-th {
            vertical-align: middle;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button type="button" onclick="generatePDF();" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm fixed-top" style="width: 100px; top: 5%; left: 90%;">
        <i class="fas fa-download fa-sm text-white-50"></i> UNDUH
    </button>
    <div class="container" id="laporantemp" data-for="{{ ucfirst($for) }}">
        <div class="container-fluid" style="top: 5%;position: relative; padding-bottom: 10%;">
            <div class="d-flex flex-row">
                <div class="sidebar-brand-icon mr-2" style="width: 95px; height: 130px;">
                    {!! $icon_cabang !!}
                </div>
                <div class="d-flex flex-column" style="width: 350px;">
                    <h3 class="text-uppercase text-dark">Laporan {{ $for }}</h3>
                    <h3 class="text-uppercase text-dark">{{ $cabang }}</h3>
                    <span class="text-dark">{{ $alamat_cabang }}</span>
                </div>
                <span class="text-uppercase text-dark ml-auto">Periode: {{ $periode }}</span>
            </div>

            <table class="table text-center table-hover table-responsive-sm text-dark" style="width:100%">
                <thead>
                    @if($for == "penjualan")
                        <tr>
                            <th>No Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nama Pemesan</th>
                            <th>Membership</th>
                            <th>CS</th>
                            <th>Subtotal</th>
                        </tr>
                    @elseif($for == "kas")
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode Kas</th>
                            <th>No Transaksi</th>
                            <th>Nama Pemesan</th>
                            <th>Keterangan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Diinput oleh</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @if($for == "penjualan")
                        @if($laporan !== null)
                        @foreach($laporan as $item)
                            <tr>
                                <td>{{ $item['no_transaksi'] }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                <td class="text-bold">{{ $item['pemesan'] }}</td>
                                <td>{{ $item['membership'] }}</td>
                                <td class="text-bold">{{ $item['cs'] }}</td>
                                <td>{{ $item['subtotal'] }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">Data Tidak Tersedia</td>
                            </tr>
                        @endif
                    @elseif($for == "kas")
                        @if($laporan !== "-")
                        <?php $no = 1; ?>
                        @foreach($laporan as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item['tanggal'] }}</td>
                                <td>{{ $item['kas'] }}</td>
                                <td>{{ $item['no_transaksi'] }}</td>
                                <td class="text-bold">{{ $item['nama_pemesan'] }}</td>
                                <td>{{ $item['keterangan'] }}</td>
                                <td>{{ $item['masuk'] }}</td>
                                <td>{{ $item['keluar'] }}</td>
                                <td class="text-bold">{{ $item['input_by'] }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">Data Tidak Tersedia</td>
                            </tr>
                        @endif
                    @elseif($for == "keuangan")
                        <tr class="table-secondary">
                            <th class="text-left">Omset Bulan Ini</th>
                            <td>{{ $laporan['omset_bulan_ini'] }}</td>
                        </tr>
                        <tr class="table-secondary">
                            <th class="text-left">Omset Bulan Lalu</th>
                            <td>{{ $laporan['omset_bulan_lalu'] }}</td>
                        </tr>
                        <tr class="table-success">
                            <th class="text-left">Omset Hari Ini</th>
                            <td>{{ $laporan['pemasukan']['omset_hari_ini'] }}</td>
                        </tr>
                        <tr class="table-success">
                            <th class="text-left">Pelunasan</th>
                            <td>{{ $laporan['pemasukan']['pelunasan_piutang'] }}</td>
                        </tr>
                        <tr class="table-danger">
                            <th class="text-left">Biaya</th>
                            <td>{{ $laporan['pengeluaran']['biaya'] }}</td>
                        </tr>
                        <tr class="table-danger">
                            <th class="text-left">Pinjaman</th>
                            <td>{{ $laporan['pengeluaran']['pinjaman'] }}</td>
                        </tr>
                        <tr class="table-danger">
                            <th class="text-left">Piutang Konsumen</th>
                            <td>{{ $laporan['piutang_bulan_ini'] }}</td>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-left">Profit Harian</th>
                            <td>{{ $laporan['profit_harian'] }}</td>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-left">Profit Bulanan</th>
                            <td>{{ $laporan['profit_bulanan'] }}</td>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-left">EDC</th>
                            <td>{{ $laporan['edc']['total'] }}</td>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-left">EDC Berjalan</th>
                            <td>{{ $laporan['edc_berjalan'] }}</td>
                        </tr>
                        <tr class="table-warning">
                            <th class="text-left">Setor Bank</th>
                            <td>{{ $laporan['setor_bank'] }}</td>
                        </tr>
                        <tr class="table-warning">
                            <th class="text-left">Setor Bank Berjalan</th>
                            <td>{{ $laporan['setor_bank_berjalan'] }}</td>
                        </tr>
                        <tr class="table-light">
                            <th></th>
                            <th></th>
                        </tr>
                        <tr class="table-danger">
                            <th class="text-left">Pengeluaran</th>
                            <th></th>
                        </tr>
                        @if($laporan['pengeluaran']['item_self'] == "-")
                            <tr class="table-light">
                                <th class="text-left">Tidak ada pengeluaran</th>
                                <th class="text-left"></th>
                            </tr>
                        @else
                            @foreach($laporan['pengeluaran']['item_self'] as $item_self)
                                <tr class="table-light">
                                    <th class="text-left">{{ $item_self['keterangan'] }}</th>
                                    <th class="text-left">{{ $item_self['subtotal'] }}</th>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="table-danger">
                            <th class="text-left">Pengeluaran Cabang</th>
                            <th></th>
                        </tr>
                        @if($laporan['pengeluaran']['item_cabang'] == "-")
                            <tr class="table-light">
                                <th class="text-left">Tidak ada pengeluaran cabang</th>
                                <th class="text-left"></th>
                            </tr>
                        @else
                            @foreach($laporan['pengeluaran']['item_cabang'] as $item_cbg)
                                <tr class="table-light">
                                    <th class="text-left">{{ $item_cbg['keterangan'] }}</th>
                                    <th class="text-left">{{ $item_cbg['subtotal'] }}</th>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="table-light">
                            <th></th>
                            <th></th>
                        </tr>
                        <tr class="table-primary">
                            <th class="text-left">EDC</th>
                            <th></th>
                        </tr>
                        @if($laporan['edc']['item'] == "-")
                            <tr class="table-light">
                                <th class="text-left">Tidak ada EDC</th>
                                <th class="text-left"></th>
                            </tr>
                        @else
                            @foreach($laporan['edc']['item'] as $item)   
                                <tr class="table-light">
                                    <th class="text-left">{{ $item['keterangan'] }}</th>
                                    <th class="text-left">{{ $item['subtotal'] }}</th>
                                </tr>
                            @endforeach
                        @endif
                    @else
                        <span class="text-center">Data Tidak Tersedia</span>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="/vendor/jquery/v3.6.0/jquery.min.js"></script>
    <script src="/vendor/jspdf/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvg/3.0.10/umd.min.js" integrity="sha512-Fud9RhwCoE9twylGwJswcFeunt7ySxn0Rm/IWS5chIpMegfAm5qFVf8/zEyvgXIKd1BYPcPW7zBBtBSJN8OC6w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module">
        // import { Canvg } from 'https://cdn.skypack.dev/canvg';
        // let v = null;
        // const canvas = document.querySelector('canvas');
        // const ctx = canvas.getContext('2d');
        // v = Canvg.fromString(ctx, $(".sidebar-brand-icon").html());
        // v.start();
        // var img = canvas.toDataURL("img/png");
        // $(".sidebar-brand-icon").html('<img src="' + img + '"/>');
        // $("canvas").remove();
    </script>
    <script>
        function generatePDF(){
            // const { jsPDF } = window.jspdf;
            // var doc = new jsPDF('p', 'pt');
            // var pdfjs = document.querySelector('#laporantemp');
            // var forLap = $(pdfjs).data("for");
            // let title = $("title").text();
            // var pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
            // var pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
            // var today = new Date();
            // var dd = String(today.getDate()).padStart(2, '0');
            // var mm = String(today.getMonth() + 1).padStart(2, '0');
            // var yyyy = today.getFullYear();
            // today = dd + '_' + mm + '_' + yyyy;
            // doc.html(pdfjs, {
            //     callback: function(doc) {
            //         doc.text(title.toUpperCase(), pageWidth / 2, pageHeight  - 10, {align: 'center'});
            //         doc.save("Laporan_" + forLap + "_" + today + ".pdf");
            //     }, margins, width: 522
            // });
            $("button").remove();
            window.print();
        }
    </script>
</body>
</html>