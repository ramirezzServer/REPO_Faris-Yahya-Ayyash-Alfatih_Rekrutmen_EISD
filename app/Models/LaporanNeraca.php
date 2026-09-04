<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LaporanNeraca extends Model
{
    protected $table = 'laporan_neraca';

    protected $fillable = [
        'kawasan_id',
        'operator_id',
        'periode_kuota_id',
        'tanggal_laporan',
        'timbulan_kg',
        'total_diolah_kg',
        'residu_kg',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_laporan' => 'date',
            'timbulan_kg' => 'decimal:2',
            'total_diolah_kg' => 'decimal:2',
            'residu_kg' => 'decimal:2',
        ];
    }

    public function kawasan(): BelongsTo
    {
        return $this->belongsTo(Kawasan::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function periodeKuota(): BelongsTo
    {
        return $this->belongsTo(PeriodeKuota::class);
    }

    public function jalurPengolahan(): BelongsToMany
    {
        return $this->belongsToMany(JalurPengolahan::class, 'detail_pengolahan')
            ->withPivot('tonase_kg', 'faktor_emisi_saat_lapor', 'keterangan')
            ->withTimestamps();
    }

    public function hitungResidu(): float
    {
        return (float) $this->timbulan_kg - (float) $this->total_diolah_kg;
    }

    public function verifikasi(): bool
    {
        $periode = $this->periodeKuota;

        if ($periode === null || ! $periode->isAktif()) {
            return false;
        }

        if ((float) $this->residu_kg > $periode->sisaKuota()) {
            return false;
        }

        $this->status = 'terverifikasi';

        return $this->save();
    }

    public function tolak(string $alasan): bool
    {
        $this->status = 'ditolak';
        $this->catatan = $alasan;

        return $this->save();
    }
}
