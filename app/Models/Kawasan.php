<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kawasan extends Model
{
    protected $table = 'kawasan';

    protected $fillable = [
        'kode_kawasan',
        'nama_rw',
        'kelurahan',
        'kecamatan',
        'jumlah_kk',
        'status_siaga',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function periodeKuota(): HasMany
    {
        return $this->hasMany(PeriodeKuota::class);
    }

    public function laporanNeraca(): HasMany
    {
        return $this->hasMany(LaporanNeraca::class);
    }

    public function laporanTumpukan(): HasMany
    {
        return $this->hasMany(LaporanTumpukan::class);
    }

    public function periodeAktif(): ?PeriodeKuota
    {
        return $this->periodeKuota()->where('status', 'aktif')->first();
    }

    public function hitungStatusSiaga(): string
    {
        $periode = $this->periodeAktif();

        if ($periode === null) {
            return 'aman';
        }

        $persentase = $periode->persentaseSisa();

        if ($persentase > 40) {
            return 'aman';
        }

        if ($persentase >= 15) {
            return 'waspada';
        }

        return 'kritis';
    }
}
