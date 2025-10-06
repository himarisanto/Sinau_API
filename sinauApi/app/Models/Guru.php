<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'nama', 
        'nip',
        'jenis_kelamin', 
        'alamat', 
        'tanggal_lahir', 
        'mata_pelajaran'
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
