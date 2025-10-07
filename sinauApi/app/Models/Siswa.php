<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nisn',
        'no_absen',
        'kelas',
        'jurusan',
        'foto',
        'tanggal_lahir',
        'jenis_kelamin',
    ];
    
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_siswa');
    }

}
