@extends('layout.main')
@section('title', 'Tambah Transaksi')
@section('container')
<?php
$fungsi = new App\Models\Fungsi;
$agent = new \Jenssegers\Agent\Agent;
?>
<div class="d-flex flex-row align-items-center">
    <h1 class="h3 mb-3 text-gray-800">Tambah Transaksi Baru</h1>
    @if($agent->isMobile())
        <div class="fixed-bottom d-flex flex-row bion-menu-fixed" id="transaksi-menu-btn">
            <button type="button" class="btn mr-auto ml-auto text-uppercase w-100" id="simpan-transaksi-btn" disabled style="background: var(--primary);color: white;border-radius: 0;height: 48px;">Simpan</button>
            <button type="button" class="btn mr-auto ml-auto text-uppercase w-100" id="bayar-transaksi-btn" data-toggle="modal" data-target="#popup-bayar" disabled style="background: var(--success);color: white;border-radius: 0;height: 48px;">Bayar Sekarang</button>
            <button type="button" class="btn mr-auto ml-auto text-uppercase w-100" id="cetak-transaksi-btn" disabled style="background: var(--warning);color: white;border-radius: 0;height: 48px;">Cetak</button>
        </div>
    @else
        <div class="ml-auto mb-3 mt-3" id="transaksi-menu-btn">
            <button type="button" class="btn btn-primary mr-2 ml-2 text-uppercase rounded-pill" id="simpan-transaksi-btn" disabled>Simpan</button>
            <button type="button" class="btn btn-success mr-2 ml-2 text-uppercase rounded-pill" id="bayar-transaksi-btn" data-toggle="modal" data-target="#popup-bayar" disabled>Bayar Sekarang</button>
            <button type="button" class="btn btn-warning mr-2 ml-2 text-uppercase rounded-pill" id="cetak-transaksi-btn" disabled>Cetak</button>
        </div>
    @endif
</div>
<div id="msgForm"></div>
<div id="contAddNew">
    @include("form")
</div>
@endsection