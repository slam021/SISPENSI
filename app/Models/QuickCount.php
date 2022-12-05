<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickCount extends Model
{
    protected $table        = 'quick_count'; 
    protected $primaryKey   = 'quick_count_id';
    
    protected $guarded = [
        'quick_count_id',
    ];
}
