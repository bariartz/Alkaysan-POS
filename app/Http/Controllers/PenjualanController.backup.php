<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan', [
            "title" => "Penjualan",
            "cabang" => "Alkaysan Lhokseumawe",
            "nama_user" => "Muhammad Zulki Akbari"
        ]);
    }

    public function index_id()
    {
        return view('transaksi', [
            "title" => "Penjualan",
            "cabang" => "Alkaysan Lhokseumawe",
            "nama_user" => "Muhammad Zulki Akbari"
        ]);
    }
}
