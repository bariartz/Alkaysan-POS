@extends("layout.main")
@section('title', 'Transaksi')
@section("container")
<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
?>
<div class="d-flex flex-row align-items-center">
    <h1 class="h3 mb-3 text-gray-800">Transaksi</h1>
    @if($agent->isMobile())
        <div class="fixed-bottom d-flex flex-row bion-menu-fixed">
            <button type="button" class="btn mr-auto ml-auto text-uppercase w-100" id="edit-transaksi-btn" style="background: var(--primary);color: white;border-radius: 0;height: 48px;">Simpan</button>
            <button type="button" class="btn mr-auto ml-auto text-uppercase w-100" id="bayar-transaksi-edit-btn" data-toggle="modal" data-target="#popup-bayar" style="background: var(--success);color: white;border-radius: 0;height: 48px;" {{ ($transaksi->Status_Bayar === "Lunas" ? "disabled" : "disabled") }}>{{ $transaksi->Status_Bayar === "Lunas" ? "Lunas" : "Bayar" }}</button>
            <a type="button" class="btn mr-auto ml-auto text-uppercase w-100" id="cetak-transaksi-btn" style="background: var(--warning);color: white;border-radius: 0;height: 48px;"  href="/penjualan/transaksi/cetak/struk/{{ $transaksi->No_Transaksi }}" target="_blank">Cetak</a>
        </div>
    @else
        <div class="ml-auto mb-3 mt-3">
            <button type="button" class="btn btn-primary mr-2 ml-2 text-uppercase rounded-pill" id="edit-transaksi-btn">Simpan</button>
            <button type="button" class="btn btn-success mr-2 ml-2 text-uppercase rounded-pill" id="bayar-transaksi-edit-btn" data-toggle="modal" data-target="#popup-bayar" {{ ($transaksi->Status_Bayar == "Lunas" ? "disabled" : "disabled") }}>{{ $transaksi->Status_Bayar == "Lunas" ? "Lunas" : "Bayar" }}</button>
            <a type="button" class="btn btn-warning mr-2 ml-2 text-uppercase rounded-pill" id="cetak-transaksi-btn" href="/penjualan/transaksi/cetak/struk/{{ $transaksi->No_Transaksi }}" target="_blank">Cetak</a>
        </div>
    @endif
</div>
<div id="msgForm"></div>
<div id="contAddNew">
    @include("form-edit")
</div>
@endsection