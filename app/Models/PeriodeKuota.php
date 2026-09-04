<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeKuota extends Model
{
    protected $table = 'periode_kuota';

    protected $fillable = [
        'kawasan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota_residu_kg',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'kuota_residu_kg' => 'decimal:2',
        ];
    }

    public function kawasan(): BelongsTo
    {
        return $this->belongsTo(Kawasan::class);
    }

    public function laporanNeraca(): HasMany
    {
        return $this->hasMany(LaporanNeraca::class);
    }

    public function kuotaTerpakai(): float
    {
        return (float) $this->laporanNeraca()
            ->where('status', 'terverifikasi')
            ->sum('residu_kg');
    }

    public function sisaKuota(): float
    {
        return (float) $this->kuota_residu_kg - $this->kuotaTerpakai();
    }

    public function persentaseSisa(): float
    {
        $total = (float) $this->kuota_residu_kg;

        if ($total == 0.0) {
            return 0;
        }

        return $this->sisaKuota() / $total * 100;
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
