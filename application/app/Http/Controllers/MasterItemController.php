<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;

class MasterItemController extends Controller
{
    public function index_all()
    {
        return view('form', [
            'index' => MasterItem::all()
        ]);
    }
}
