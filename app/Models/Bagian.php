<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
