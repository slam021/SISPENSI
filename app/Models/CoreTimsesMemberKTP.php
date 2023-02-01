<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreTimsesMemberKTP extends Model
{
    protected $table        = 'core_timses_member_ktp'; 
    protected $primaryKey   = 'timses_member_ktp_id';
    
    protected $guarded = [
        'timses_member_ktp_id',
    ];
}
