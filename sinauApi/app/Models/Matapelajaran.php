<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Materi;

class Matapelajaran extends Model
{
    protected $table = 'matapelajarans';
    protected $hidden = ['pivot'];


    protected $fillable = [
        'nama_matapelajaran',
    ];

    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'guru_matapelajaran', 'matapelajaran_id', 'guru_id')
                    ->withTimestamps();
    }

    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'id_matapelajaran');
    }

}
