<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreCandidatePartai extends Model
{
    protected $table        = 'core_candidate_partai'; 
    protected $primaryKey   = 'partai_id';
    
    protected $guarded = [
        'partai_id',
    ];
}