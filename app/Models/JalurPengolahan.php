<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JalurPengolahan extends Model
{
    protected $table = 'jalur_pengolahan';

    protected $fillable = [
        'nama',
        'kategori',
        'faktor_emisi_co2',
        'ikon',
        'deskripsi',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
            'faktor_emisi_co2' => 'decimal:4',
        ];
    }

    public function laporanNeraca(): BelongsToMany
    {
        return $this->belongsToMany(LaporanNeraca::class, 'detail_pengolahan')
            ->withPivot('tonase_kg', 'faktor_emisi_saat_lapor', 'keterangan')
            ->withTimestamps();
    }

    public function totalTonase(): float
    {
        return (float) $this->laporanNeraca()
            ->where('laporan_neraca.status', 'terverifikasi')
            ->sum('detail_pengolahan.tonase_kg');
    }
}
