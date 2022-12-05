<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationProgram extends Model
{
    protected $table        = 'program_documentation'; 
    protected $primaryKey   = 'program_documentation_id';
    
    protected $guarded = [
        'program_documentation_id',
    ];
}
