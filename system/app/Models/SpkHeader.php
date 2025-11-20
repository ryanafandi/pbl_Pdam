<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SpkHeader extends Model
{
    protected $table = 'spk_header';
    public $timestamps = false; // pakai dibuat_at / disetujui_at sendiri

    protected $fillable = [
        'rab_id',
        'nomor_spk',
        'pekerjaan',
        'nama_pelanggan',
        'alamat',
        'lokasi',
        'no_pelanggan',
        'catatan',
        'status',
        'dibuat_at',
        'disetujui_at',

        // === TAMBAHKAN DUA BARIS INI ===
        'tgl_jadwal',    // <--- Agar tanggal tersimpan
        'status_teknis', // <--- Agar status berubah jadi 'scheduled'
    ];

    protected $casts = [
        'rab_id'       => 'integer',
        'dibuat_at'    => 'datetime',
        'disetujui_at' => 'datetime',
        'tgl_jadwal'   => 'datetime', // Tambahkan casting ini juga agar format tanggal aman
    ];

    /* ================= RELASI ================= */

    public function rab()
    {
        return $this->belongsTo(RabHeader::class, 'rab_id', 'id');
    }

    /* ============== ACCESSOR / HELPER ============== */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'kirim_direktur' => 'Dikirim ke Direktur',
            'disetujui'      => 'Disetujui Direktur',
            'ditolak'        => 'Ditolak Direktur',
            'selesai'        => 'Selesai Pemasangan',
            default          => 'Draft',
        };
    }

    /**
     * Generate nomor SPK otomatis.
     * Sekarang format: SPK-YYYYMM-XXXX (misal: SPK-202511-0001)
     * Kalau mau format lain (001/SPK/...), nanti bisa kita ubah di sini saja.
     */
    public static function generateNomorSpk(): string
    {
        $prefix = 'SPK-' . date('Ym') . '-';

        $last = static::where('nomor_spk', 'like', $prefix . '%')
            ->orderByDesc('nomor_spk')
            ->first();

        $next = 1;
        if ($last && preg_match('/(\d{4})$/', $last->nomor_spk, $m)) {
            $next = (int) $m[1] + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /* ============= LOGIKA STATUS ============= */

    /** Kirim SPK ke direktur */
    public function sendToDirector(): void
    {
        if ($this->status !== 'draft') {
            throw new \RuntimeException('SPK ini sudah dikirim atau diproses.');
        }

        // kalau mau sekalian isi waktu dibuat_at:
        $this->dibuat_at = $this->dibuat_at ?? Carbon::now();
        $this->status    = 'kirim_direktur';
        $this->save();
    }

    /** Direktur menyetujui SPK */
    public function approveByDirector(): void
    {
        if ($this->status !== 'kirim_direktur') {
            throw new \RuntimeException('SPK harus dalam status "kirim_direktur" untuk disetujui.');
        }

        $this->status       = 'disetujui';
        $this->disetujui_at = Carbon::now();
        $this->save();
    }

    /** Direktur menolak SPK, opsional ada catatan */
    public function rejectByDirector(?string $note = null): void
    {
        if ($this->status !== 'kirim_direktur') {
            throw new \RuntimeException('SPK harus dalam status "kirim_direktur" untuk ditolak.');
        }

        $this->status       = 'ditolak';
        $this->disetujui_at = null;

        $note = trim((string) $note);
        if ($note !== '') {
            $this->catatan = ($this->catatan ? $this->catatan . "\n" : '') . '[Direktur] ' . $note;
        }

        $this->save();
    }

    /** Tandai SPK selesai pemasangan (Tim Trandis) */
    public function markFinished(): void
    {
        if ($this->status !== 'disetujui') {
            throw new \RuntimeException('Hanya SPK yang sudah disetujui yang bisa ditandai selesai.');
        }

        $this->status = 'selesai';
        $this->save();
    }

    // ... (kode sebelumnya) ...

    // === TAMBAHAN RELASI TRANDIS ===
    public function logs()
    {
        return $this->hasMany(SpkLog::class, 'spk_header_id');
    }

    public function fotos()
    {
        return $this->hasMany(SpkFoto::class, 'spk_header_id');
    }

    // Cek apakah timer sedang jalan?
    public function getIsWorkingAttribute()
    {
        return $this->logs()->whereNull('selesai_pada')->exists();
    }

    public function getActiveLogAttribute()
    {
        return $this->logs()->whereNull('selesai_pada')->latest()->first();
    }
}
