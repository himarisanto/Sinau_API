<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'kelas_id',
        // 'jurusan',
    ];

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'guru_siswa', 'guru_id', 'siswa_id')
            ->withTimestamps();
    }

    /**
     * Guru belongs to a single kelas via `kelas_id` column.
     * Keeps compatibility if you later switch to a pivot table.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(KelasModel::class, 'kelas_id');
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
    public function materiDariMapel()
    {
        return $this->hasManyThrough(
            \App\Models\Materi::class,
            \App\Models\Matapelajaran::class,
            'id',
            'id_matapelajaran',
            'id',
            'id'
        );
    }
    public function materis()
    {
        return $this->belongsToMany(Materi::class, 'guru_materi', 'guru_id', 'materi_id');
    }
}
