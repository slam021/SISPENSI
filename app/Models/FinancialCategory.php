<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
{
    protected $table        = 'financial_category'; 
    protected $primaryKey   = 'financial_category_id';
    
    protected $guarded = [
        'financial_category_id',
    ];
}
