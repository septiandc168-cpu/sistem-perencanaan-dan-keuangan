<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKegiatan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'rencana_kegiatans';
}

