<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanTumpukan extends Model
{
    protected $table = 'laporan_tumpukan';

    protected $fillable = [
        'user_id',
        'kawasan_id',
        'lokasi',
        'deskripsi',
        'foto',
        'status',
        'catatan_tindak_lanjut',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kawasan(): BelongsTo
    {
        return $this->belongsTo(Kawasan::class);
    }
}
