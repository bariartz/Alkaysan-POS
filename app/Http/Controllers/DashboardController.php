<?php

namespace App\Http\Controllers;

use App\Models\BayarTransaksi;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Kas;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use App\Models\Fungsi;
use App\Models\ItemTransaksi;
use App\Models\Produk;
use App\Models\MasterItem;
use App\Models\perusahaan;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function home(Request $request, $page = null)
    {
        $store = isset($request->store) ? $request->store : Session::get('store');
        if(isset($request->store) && $request->store !== "") {
            $success = false;
            $app = null;

            if($request->session()->get('store')) {
                $store = $request->session()->get('store');
            }
            
            // $request->session()->put('store', $store);
            // DB::purge('mysql');
            // Config::set('database.connections.mysql.database', env('DB_DATABASE') . '_' . $store);

            try {
                $app = [
                    "menu" => showMenu(auth()->user()),
                ];
                $success = true;
            } catch (\Exception $e){
                $getMenuList = null;
            }
            
            return response()->json([
                'success' => $success,
                'app' => $app
            ]);
        }
        
        return Inertia::render('Welcome', [
            'flash' => [
                'message' => $request->session()->get('login_message')
            ],
            'store' => $store
        ]);
    }

    public function goStore(Request $request)
    {
        $store = $request->store;
        if($request->session()->get('store')) {
            $store = $request->session()->get('store');
        }
        
        $request->session()->put('store', $store);

        return response()->json(['success' => true], 200);
    }
}
