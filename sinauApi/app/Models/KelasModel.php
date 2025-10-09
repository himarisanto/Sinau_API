<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    protected $fillable = [
        'nama_kelas'
    ];
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
