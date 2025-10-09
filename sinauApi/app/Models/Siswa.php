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
        'kelas_id',
        'jurusan',
        'foto',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_siswa', 'siswa_id', 'guru_id')
                    ->withTimestamps();
    }

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'kelas_id');
    }
}
