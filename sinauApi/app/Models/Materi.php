<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    protected $table = 'materis';

    protected $fillable = [
        'judul',
        'deskripsi',
        'konten',
        'id_matapelajaran',
    ];

    public function matapelajaran(): BelongsTo
    {
        return $this->belongsTo(Matapelajaran::class, 'id_matapelajaran');
    }
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_materi', 'materi_id', 'guru_id')
            ->withTimestamps();
    }
}
