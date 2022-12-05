<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreTimses extends Model
{
    protected $table        = 'core_timses'; 
    protected $primaryKey   = 'timses_id';
    
    protected $guarded = [
        'timses_id',
    ];
}
