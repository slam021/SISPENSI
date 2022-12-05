<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreSupporter extends Model
{
    protected $table = 'core_supporter';
    protected $primaryKey = 'supporter_id';

    protected $guarded = [
        'supporter_id',
    ];
}
