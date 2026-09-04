<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengolahan extends Model
{
    protected $table = 'detail_pengolahan';

    protected $fillable = [
        'laporan_neraca_id',
        'jalur_pengolahan_id',
        'tonase_kg',
        'faktor_emisi_saat_lapor',
        'keterangan',
    ];

    public function laporanNeraca(): BelongsTo
    {
        return $this->belongsTo(LaporanNeraca::class);
    }

    public function jalurPengolahan(): BelongsTo
    {
        return $this->belongsTo(JalurPengolahan::class);
    }

    public function hitungEmisi(): float
    {
        return (float) $this->tonase_kg * (float) $this->faktor_emisi_saat_lapor;
    }
}
