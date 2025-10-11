<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guru extends Model
{
    protected $table = 'gurus';
    protected $hidden = ['pivot'];

    protected $fillable = [
        'nama', 
        'nip',
        'jenis_kelamin', 
        'alamat', 
        'tanggal_lahir', 
        // 'mata_pelajaran'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'guru_siswa', 'guru_id', 'siswa_id');
    }

    public function matapelajarans(): BelongsToMany
    {
        return $this->belongsToMany(Matapelajaran::class, 'guru_matapelajaran', 'guru_id', 'matapelajaran_id');
    }
}