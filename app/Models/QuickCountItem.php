<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickCountStarting extends Model
{
    protected $table        = 'quick_count_item'; 
    protected $primaryKey   = 'quick_count_item_id';
    
    protected $guarded = [
        'quick_count_item_id',
    ];
}
