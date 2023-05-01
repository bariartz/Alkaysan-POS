<?php
$fungsi = new App\Models\Fungsi;
$DB = new Illuminate\Support\Facades\DB;
?>
@extends("v1.layout.main")
@section('title', 'Dashboard')
@section("container")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div id="msgForm" class="mb-5"></div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Penghasilan Harian | {{ date("d") . " " . $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $penghasilan_harian }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Penghasilan Bulanan | {{ $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $penghasilan_bulanan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Klik A3 | {{ $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $a3 }}</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: 0" aria-valuenow="0" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Item belum diproses | {{ date("d") . " " . $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $belumdiprosesharian }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tinjauan Penghasilan Bulanan | {{ $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}</h6>
                @foreach($totalpenghasilan as $penghasilan)
                    <div id="penghasilan" data-total-penghasilan="{{ $fungsi->decimalNumb($penghasilan->penghasilan) }}"></div>
                @endforeach
                @foreach($totalhpp as $hpp)
                    <div id="hpp" data-total-hpp="{{ $fungsi->decimalNumb($hpp->hpp) }}"></div>
                @endforeach
                @foreach($totalpengeluaran as $pengeluaran)
                    <div id="pengeluaran" data-total-pengeluaran="{{ $fungsi->decimalNumb($pengeluaran->pengeluaran) }}"></div>
                @endforeach
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Sumber Pemasukan Bulanan | {{ $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(count($kategorinow) !== 0)
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            @foreach($kategorinow as $kategori)
                                <span class="mr-2" id="labelpie" data-label="{{ $kategori->Kategori }}" data-color-label="{{ $fungsi->colorLbl() }}" data-jumlah-label="{{ $kategori->total_kategori }}">
                                    <i class="fas fa-circle"></i> {{ $kategori->Kategori }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center small">
                            <span class="mr-2">
                                Belum Ada Data
                            </span>
                        </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status Orderan Bulanan | {{ $fungsi->convertBulanShort(date("M")) . " " . date("Y") }}</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Belum diproses <span
                        class="float-right">{{ $belumdiproses }} Item</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: calc(100%/{{ $belumdiproses }});"
                        aria-valuenow="{{ $belumdiproses }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Sedang di desain <span
                        class="float-right">{{ $sedangdidesain }} Item</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: calc(100%/{{ $sedangdidesain }});"
                        aria-valuenow="{{ $sedangdidesain }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Proses cetak <span
                        class="float-right">{{ $prosescetak }} Item</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: calc(100%/{{ $prosescetak }});"
                        aria-valuenow="{{ $prosescetak }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Selesai dicetak <span
                        class="float-right">{{ $selesaidicetak }} Item</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: calc(100%/{{ $selesaidicetak }});"
                        aria-valuenow="{{ $selesaidicetak }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Sudah diambil <span
                        class="float-right">{{ $sudahdiambil }} Item</span></h4>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: calc(100%/{{ $sudahdiambil }});"
                        aria-valuenow="{{ $sudahdiambil }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
            </div>
            <div class="card-body">
                <?php 
                    // $kategoriLow = "";
                    // $kategoriUp = "";
                    // $kategoriLower = "";
                    // $ringkasan = "";
                    // $predictInc = "";

                    // foreach($kategoriproduk as $kate){
                    //     if($kate->total_kategori < 5){
                    //         $kategoriLow .= "<li>" . ucfirst(strtolower($kate->Kategori)) . " (" . $kate->total_kategori . " " . $kate->Satuan . ")</li>";
                    //     } else {
                    //         foreach($kategoriprodukbulanlalu as $kat){
                    //             if($kat->Kategori == $kate->Kategori){
                    //                 if($kate->total_kategori > $kat->total_kategori){
                    //                     $persenKat = "naik";
                    //                     $totalPersenKat = number_format(round((($kate->total_kategori - $kat->total_kategori) / $kate->total_kategori * 100), 1));
                    //                     $kategoriUp .= "<li>" . ucfirst(strtolower($kate->Kategori)) . " +$totalPersenKat%</li>"; 
                    //                 } else if($kate->total_kategori < $kat->total_kategori){
                    //                     $persenKat = "turun";
                    //                     $totalPersenKat = number_format(round((($kat->total_kategori - $kate->total_kategori) / $kat->total_kategori * 100), 1));
                    //                     $kategoriLower .= "<li>" . ucfirst(strtolower($kate->Kategori)) . " -$totalPersenKat%</li>";
                    //                 }
                    //             }
                    //         }
                    //     }

                    //     //Sales Forecasting
                    //     //* Data Mining
                    //     $dataC3M = $DB::table('master_items')
                    //     ->join('item_transaksis', 'item_transaksis.Kode_Produk', '=', 'master_items.Kode_Produk')
                    //     ->selectRaw("DATE_FORMAT(item_transaksis.Tanggal_Transaksi, '%Y') AS tahun, DATE_FORMAT(item_transaksis.Tanggal_Transaksi, '%m') AS bulan, COUNT(*) AS total_penjualan")
                    //     ->where("master_items.Kategori", '=', $kate->Kategori)
                    //     ->groupBy($DB::raw("DATE_FORMAT(item_transaksis.Tanggal_Transaksi, '%Y%m')"))
                    //     ->orderByRaw('tahun DESC, bulan DESC')
                    //     ->limit(3)
                    //     ->get();

                    //     //* Prediksi
                    //     $C3M = 0;
                    //     $period = 12; // Period 12 Month
                    //     $getLM = date("m", strtotime("-1 month"));
                    //     $getLM2 = date("m", strtotime("-2 month"));
                    //     $totalLM = 0;
                    //     $totalLM2 = 0;
                    //     foreach($dataC3M as $data){
                    //         $C3M += $data->total_penjualan;
                    //         $bulan = $data->bulan;
                    //         $tahun = $data->tahun;
                    //         if(date("Y") !== $tahun){
                    //             $tahun = $data->tahun;
                    //         }

                    //         if($bulan == $getLM){
                    //             $totalLM = $data->total_penjualan;
                    //         }

                    //         if($bulan == $getLM2){
                    //             $totalLM2 = $data->total_penjualan;
                    //         }
                    //     }
                    //     //** SMA (Single Moving Averages)
                    //     $SMA = round($C3M / $period);
                    //     //** MAD (Mean Absolute Deviation)
                    //     $MAD = round(($C3M - $SMA) / $period);
                    //     //** LMMAD2 (Last 2 Month MAD)
                    //     $LMMAD2 = round($totalLM2 - $MAD);
                    //     // Return Info Penurunan or Kenaikan
                    //     $decOrInc = ($totalLM > $LMMAD2 ? "Penurunan" : "Kenaikan");
                    //     if($decOrInc == 'Penurunan'){
                    //         $persenNya = number_format(round((($totalLM - $LMMAD2) / $totalLM * 100), 1)) . "%";
                    //     } else if($decOrInc == 'Kenaikan'){
                    //         $persenNya = "+" . number_format(round((($LMMAD2 - $totalLM) / $totalLM * 100), 1)) . "%";
                    //         if($persenNya !== "+0%"){
                    //             $predictInc .= "<li>" . ucfirst(strtolower($kate->Kategori)) . " " . $persenNya . "</li>";
                    //         }
                    //     } else {
                    //         $persenNya = "-";
                    //     }
                    // }
                ?>
                <p>Klik A3 bulan lalu ({{ $fungsi->convertBulanLong(date("F", strtotime("-1 month"))) . " " . $tahun }}) : {{ $a3_bulan_lalu }} klik</p>
                <p>Penjualan yang terjual dibawah 5 item:</p>
                <ul style="columns: 2;-webkit-columns: 2;-moz-columns: 2;">
                    {!! $kategoriLow !!}
                </ul>
                <p>Penjualan yang mengalami kenaikan di bulan terakhir ({{ $fungsi->convertBulanLong(date("F", strtotime("-1 month"))) . " " . $tahun }}) :</p>
                <ul style="columns: 2;-webkit-columns: 2;-moz-columns: 2;">
                    {!! $kategoriUp !!}
                </ul>
                <p>Penjualan yang mengalami penurunan di bulan terakhir ({{ $fungsi->convertBulanLong(date("F", strtotime("-1 month"))) . " " . $tahun }}) :</p>
                <ul style="columns: 2;-webkit-columns: 2;-moz-columns: 2;">
                    {!! $kategoriLower !!}
                </ul>
                <p>Dari ringkasan bulan ini, silahkan promosikan produk yang mengalami penurunan atau terjual dibawah 5 pcs.</p>
                <p>Kemungkinan produk yang akan mengalami kenaikan di bulan ini ({{ $fungsi->convertBulanLong(date("F")) . " " . $tahun }})</p>
                <ul style="columns: 2;-webkit-columns: 2;-moz-columns: 2;">
                    {!! $predictInc !!}
                </ul>
                <span>*Note: Jumlah dalam persen kemungkinan naik jika dipromosikan.</span>
            </div>
        </div>
    </div> --}}
</div>
@endsection