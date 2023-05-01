<?php
use App\Http\Controllers\TransaksiController;

Route::group(['middleware' => ['auth']], function() {
  Route::group(['prefix' => '{cabang}/penjualan'], function() {
    Route::get('/', [TransaksiController::class, 'index']);
    Route::get('tabel', [TransaksiController::class, 'tabel_index']);
    Route::get('tabel/{tglawal}/{tglakhir}', [TransaksiController::class, 'penjualan_index_sort']);
    Route::get('add', [TransaksiController::class, 'index_add']);
    Route::get('transaksi/edit/{transaksi:No_Transaksi}', [TransaksiController::class, 'index_id']);
    Route::get('/transaksi/edit/{noTransaksi}/form', [TransaksiController::class, 'index_id_form']);
    Route::get('transaksi/cetak/struk/{transaksi:No_Transaksi}', [TransaksiController::class, 'struk_penjualan']);
  });
});