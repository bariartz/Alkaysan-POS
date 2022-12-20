@extends("layout.main")
@section('title', 'Penjualan')
@section("container")
<h1 class="h3 mb-3 text-gray-800">Penjualan <a type="button" href="/{{ $cabang }}/penjualan/add" class="btn btn-outline-danger">Tambah</a></h1>
<div id="msgForm"></div>
<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
?>
@include("partial.laporan-menu")
@if($agent->isDesktop())
<div class="card shadow mb-4">
    <div class="card-body" id="tabelpenjualan">
        <div class="d-flex flex-row mb-2">
            <div class="dataTables_length mr-5" id="penjualanTable_length">
                <label>
                    Tampilkan
                    <div class="selection ui dropdown" tabindex="0" style="min-width: auto;">
                        <select name="penjualanTable_length" aria-controls="penjualanTable">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                        </select>
                        <i class="dropdown icon"></i>
                        <div class="text">25</div>
                        <div class="menu transition" tabindex="-1">
                            <div class="item active selected" data-value="25">25</div>
                            <div class="item" data-value="50">50</div>
                            <div class="item" data-value="100">100</div>
                            <div class="item" data-value="250">250</div>
                        </div>
                    </div>
                    baris
                </label>
            </div>
        
            <div class="dataTables_length" id="penjualanTable_daterange">
                <label class="d-flex flex-row align-items-center">
                    Tanggal
                    <div id="reportrangekas" class="selection ui dropdown ml-2" data-tgl-awal="{{ date("Y-m-d") }}" data-tgl-akhir="{{ date("Y-m-d") }}">
                        <i class="far fa-calendar-alt"></i>&nbsp;
                        <span>{{ date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") . " - " . date("d") . " " . $fungsi->convertBulanLong(ucfirst(date("F"))) . " " . date("Y") }}</span>
                        <i class="fa fa-caret-down"></i>
                    </div>
                    <span class="ml-2" style="inline-size: 300px;">Info: Semakin lama pemilihan periode waktu, semakin lama memuat datanya.</span> 
                </label>
            </div>
        </div>
        <div class="d-flex flex-row mb-4">
            <div class="d-flex flex-column">
                <h4 class="text-bold mb-0">Info Warna</h4>
                <div class="d-flex flex-row">
                    <div class="d-flex flex-row align-items-center mr-3">
                        <div class="mr-2 table-success" style="width: 15px;height: 15px;"></div>
                        <span>Lunas</span>
                    </div>
                    <div class="d-flex flex-row align-items-center mr-3">
                        <div class="mr-2 table-warning" style="width: 15px;height: 15px;"></div>
                        <span>Belum Lunas</span>
                    </div>
                    <div class="d-flex flex-row align-items-center mr-3">
                        <div class="mr-2 table-danger" style="width: 15px;height: 15px;"></div>
                        <span>Sudah Diambil</span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row align-items-center ml-auto" style="width: 300px;">
                <i class="fas fa-search mr-2"></i>
                <input type="text" class="form-control" id="searchTable" placeholder="Cari no invoice">
            </div>
        </div>
        @include('tabelpenjualan')

        <div class='pagination-container'>
            <nav>
                <ul class="pagination">
                    <li data-page="prev" class="page__btn">
                        <span class="fa fa-chevron-left">
                            <span class="sr-only">(current)</span>
                        </span>
                    </li>
                    <li data-page="next" id="prev" class="page__btn">
                        <span class="fa fa-chevron-right">
                            <span class="sr-only">(current)</span>
                        </span>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@else
<div class="d-flex flex-row mb-2">
    <div class="dataTables_length mr-5" id="penjualanTable_length">
        <label>
            Tampilkan
            <div class="selection ui dropdown" tabindex="0" style="min-width: auto;">
                <select name="penjualanTable_length" aria-controls="penjualanTable">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                </select>
                <i class="dropdown icon"></i>
                <div class="text">25</div>
                <div class="menu transition" tabindex="-1">
                    <div class="item active selected" data-value="25">25</div>
                    <div class="item" data-value="50">50</div>
                    <div class="item" data-value="100">100</div>
                    <div class="item" data-value="250">250</div>
                </div>
            </div>
            baris
        </label>
    </div>

    <div class="dataTables_length" id="penjualanTable_daterange">
        <label>
            Tanggal
            <div id="reportrangekas" class="selection ui dropdown" data-tgl-awal="{{ date("Y-m-d") }}" data-tgl-akhir="{{ date("Y-m-d") }}">
                <i class="far fa-calendar-alt"></i>&nbsp;
                <span>{{ date("d-m-Y") . " - " . date("d-m-Y") }}</span>
                <i class="fa fa-caret-down"></i>
            </div>
        </label>
    </div>
</div>
<div class="d-flex flex-column mb-4">
    <h4 class="text-bold mb-0">Info Warna</h4>
    <div class="d-flex flex-row">
        <div class="d-flex flex-row align-items-center mr-3">
            <div class="mr-2 table-success" style="width: 15px;height: 15px;"></div>
            <span>Lunas</span>
        </div>
        <div class="d-flex flex-row align-items-center mr-3">
            <div class="mr-2 table-warning" style="width: 15px;height: 15px;"></div>
            <span>Belum Lunas</span>
        </div>
    </div>
</div>
<div id="tabelpenjualan">
    @include('tabelpenjualan')
</div>
@endif
@endsection
