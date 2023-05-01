<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $database = 'app';
    protected $table = 'menu';

    public function childMenu()
    {
        return $this->hasMany(Menu::class, 'groupId', 'id');
    }
}
