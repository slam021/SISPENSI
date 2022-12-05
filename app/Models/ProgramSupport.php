<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSupport extends Model
{
    protected $table        = 'program_support'; 
    protected $primaryKey   = 'program_support_id';
    
    protected $guarded = [
        'program_support_id',
    ];
}
