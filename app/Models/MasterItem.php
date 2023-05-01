<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterItem extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'Kode_Produk'];
    public $timestamps = false;
}
