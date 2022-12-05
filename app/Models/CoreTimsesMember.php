<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreTimsesMember extends Model
{
    protected $table        = 'core_timses_member'; 
    protected $primaryKey   = 'timses_member_id';
    
    protected $guarded = [
        'timses_member_id',
    ];
}
