<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'jenis_kelamin',
        'alamat',
        'tanggal_lahir',
    ];

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'guru_siswa', 'guru_id', 'siswa_id')
            ->withTimestamps();
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(KelasModel::class, 'guru_kelas', 'guru_id', 'kelas_id')
            ->withTimestamps();
    }

    public function siswasMelaluiKelas(): HasManyThrough
    {
        return $this->hasManyThrough(
            Siswa::class,
            KelasModel::class,
            'id',
            'kelas_id',
            'id',
            'id'
        );
    }
    public function matapelajarans()
    {
        return $this->belongsToMany(Matapelajaran::class, 'guru_matapelajaran', 'guru_id', 'matapelajaran_id')
            ->withTimestamps();
    }
    
}
