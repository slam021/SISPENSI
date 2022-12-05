<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialFlow extends Model
{
    protected $table        = 'financial_flow'; 
    protected $primaryKey   = 'financial_flow_id';
    
    protected $guarded = [
        'financial_flow_id',
    ];
}
