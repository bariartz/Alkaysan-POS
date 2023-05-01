<?php

Route::group(['middleware' => ['auth']], function() {
  Route::group(['prefix' => '{cabang}/kas'], function() {
    Route::get('/', 'TransaksiController@kas_index');
    Route::get('tabel/{kodekas}/{tglawal}/{tglakhir}', "TransaksiController@kas_index_sort");
    Route::get('tabel', "TransaksiController@kas_index_full");
  });
});