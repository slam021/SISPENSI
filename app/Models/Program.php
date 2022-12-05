<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table        = 'program'; 
    protected $primaryKey   = 'program_id';
    
    protected $guarded = [
        'program_id',
    ];
}
