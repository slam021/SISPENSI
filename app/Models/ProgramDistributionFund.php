<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDistributionFund extends Model
{
    protected $table        = 'program_distribution_fund'; 
    protected $primaryKey   = 'distribution_fund_id';
    
    protected $guarded = [
        'distribution_fund_id',
    ];
}
