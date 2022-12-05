<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreCandidate extends Model
{
    protected $table        = 'core_candidate'; 
    protected $primaryKey   = 'candidate_id';
    
    protected $guarded = [
        'candidate_id',
    ];
}