<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    protected $table = 'siswas';

    protected $fillable = [
        'nama',
        'nisn',
        'no_absen',
        'kelas_id',
        'jurusan_id',
        'foto',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/images/' . $this->foto) : null;
    }
    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'guru_siswa', 'siswa_id', 'guru_id')
            ->withTimestamps();
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(KelasModel::class, 'kelas_id');
    }

    /**
     * Semua jawaban yang dikirim oleh siswa ini.
     */
    public function jawabans(): HasMany
    {
        return $this->hasMany(\App\Models\Jawaban::class, 'siswa_id');
    }
}
