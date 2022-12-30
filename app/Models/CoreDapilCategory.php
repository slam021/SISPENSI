<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreDapilCategory extends Model
{
    protected $table        = 'core_dapil_category'; 
    protected $primaryKey   = 'dapil_category_id';
    
    protected $guarded = [
        'dapil_category_id',
    ];
}
