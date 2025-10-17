<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    use HasFactory;

    // match the migration table name
    protected $table = 'tugas';
    

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'deadline',
        'guru_id',
        'kelas_id',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(KelasModel::class, 'kelas_id');
    }

    /**
     * Semua jawaban untuk tugas ini (one tugas -> many jawaban)
     */
    public function jawabans(): HasMany
    {
        return $this->hasMany(Jawaban::class, 'tugas_id');
    }
}
