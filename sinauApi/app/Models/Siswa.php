<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'jurusan',
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

public function kelas(): BelongsTo
{
    return $this->belongsTo(KelasModel::class, 'kelas_id');
}

}
