<?php

Route::group(['middleware' => ['auth']], function() {
  Route::group(['prefix' => '{cabang}/product'], function() {
    Route::get('/', 'TransaksiController@master_item');
  });
});