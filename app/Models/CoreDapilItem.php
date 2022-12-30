<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreDapilItem extends Model
{
    protected $table        = 'core_dapil_item'; 
    protected $primaryKey   = 'dapil_item_id';
    
    protected $guarded = [
        'dapil_item_id',
    ];
}
