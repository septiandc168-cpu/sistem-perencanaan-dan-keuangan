<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    protected $fillable = [
        'rencana_kegiatan_id',
        'pelaksanaan_kegiatan',
        'hasil_kegiatan',
        'kendala',
        'evaluasi',
        'dokumentasi',
    ];

    protected $casts = [
        'dokumentasi' => 'array',
    ];

    /**
     * Get the rencana kegiatan that owns the laporan.
     */
    public function rencanaKegiatan()
    {
        return $this->belongsTo(RencanaKegiatan::class, 'rencana_kegiatan_id', 'uuid');
    }

    /**
     * Check if laporan can be created for the given rencana kegiatan.
     */
    public static function canCreateFor(RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only allow laporan for completed rencana kegiatan
        if ($rencanaKegiatan->status !== RencanaKegiatan::STATUS_SELESAI) {
            return false;
        }

        // Check if laporan already exists
        return !static::where('rencana_kegiatan_id', $rencanaKegiatan->uuid)->exists();
    }

    /**
     * Get the status label for the rencana kegiatan.
     */
    public function getRencanaStatusLabel(): string
    {
        return $this->rencanaKegiatan ? 
            RencanaKegiatan::getStatusOptions()[$this->rencanaKegiatan->status] ?? 'Unknown' 
            : 'Unknown';
    }
}
