<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KelasModel;

class Jurusan extends Model
{
    protected $table = 'jurusans';

    protected $fillable = [
        'nama_jurusan'
    ];
    public function kelas()
    {
        return $this->hasMany(KelasModel::class, 'jurusan_id');
    }
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'jurusan_id');
    }
}
