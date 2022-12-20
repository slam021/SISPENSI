<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTimsesActivity extends Model
{
    protected $table        = 'program_timses_activity'; 
    protected $primaryKey   = 'program_timses_activity_id';
    
    protected $guarded = [
        'program_timses_activity_id',
    ];
}
