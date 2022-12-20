<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Session;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;
}
