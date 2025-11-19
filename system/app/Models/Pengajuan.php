<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // === Status Persetujuan ===
    public const ST_SUBMITTED        = 'SUBMITTED';
    public const ST_SENT_TO_DIRECTOR = 'SENT_TO_DIRECTOR';
    public const ST_APPROVED         = 'APPROVED';
    public const ST_REJECTED         = 'REJECTED';

    // === Status Progres Pemasangan (untuk pelanggan) ===
    public const PG_QUEUED     = 'QUEUED';          // antrian
    public const PG_SURVEY     = 'SURVEY';          // survei lapangan
    public const PG_MATERIAL   = 'MATERIAL_READY';  // material siap
    public const PG_SCHEDULED  = 'SCHEDULED';       // jadwal terbit
    public const PG_INSTALLING = 'INSTALLING';      // pemasangan berlangsung
    public const PG_INSTALLED  = 'INSTALLED';       // selesai pemasangan

    protected $fillable = [
        // data pendaftaran
        'no_pendaftaran',
        'pemohon_nama',
        'nomor_telepon',
        'pekerjaan',
        'alamat_pemasangan',
        'penghuni_tetap',
        'penghuni_tidak_tetap',
        'email',
        'peruntukan',
        'jumlah_kran',
        'langganan_ke_orang_lain',
        'pelanggan_terdekat',
        'ktp_url',
        'kk_url',
        'foto_rumah_url',
        'denah_url',
        'setuju_pernyataan',
        'setuju_waktu',
        'setuju_ip',

        // persetujuan
        'status',               // SUBMITTED / SENT_TO_DIRECTOR / APPROVED / REJECTED
        'catatan_admin',
        'catatan_direktur',
        'sent_to_director_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',

        // progres pemasangan (dipantau pelanggan)
        'progress_status',      // QUEUED / SURVEY / MATERIAL_READY / SCHEDULED / INSTALLING / INSTALLED
        'progress_updated_at',
    ];

    protected $casts = [
        'penghuni_tetap'          => 'integer',
        'penghuni_tidak_tetap'    => 'integer',
        'jumlah_kran'             => 'integer',
        'langganan_ke_orang_lain' => 'boolean',
        'setuju_pernyataan'       => 'boolean',
        'setuju_waktu'            => 'datetime',

        'sent_to_director_at'     => 'datetime',
        'approved_at'             => 'datetime',
        'rejected_at'             => 'datetime',
        'progress_updated_at'     => 'datetime',
    ];

    /* =========================
     |  RELATIONS
     * ========================= */
    /** 1 pengajuan = maksimal 1 SPKO */
    public function spko()
    {
        return $this->hasOne(\App\Models\Spko::class, 'pengajuan_id', 'id');
    }

    /* =========================
     |  SCOPES (filter siap pakai)
     * ========================= */
    /** Pengajuan siap dibuatkan SPKO (APPROVED & belum punya SPKO) */
    public function scopeSiapSpko($q)
    {
        return $q->where('status', self::ST_APPROVED)
            ->whereDoesntHave('spko');
    }

    /** Pencarian sederhana untuk list admin */
    public function scopeSearch($q, ?string $s)
    {
        if (!$s) return $q;
        $s = trim($s);
        return $q->where(function ($w) use ($s) {
            $w->where('no_pendaftaran', 'like', "%$s%")
                ->orWhere('pemohon_nama', 'like', "%$s%")
                ->orWhere('alamat_pemasangan', 'like', "%$s%");
        });
    }

    /* =========================
     |  ACCESSORS / HELPERS
     * ========================= */
    /** Kelas badge status persetujuan */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::ST_SUBMITTED         => 'badge-secondary',
            self::ST_SENT_TO_DIRECTOR  => 'badge-warning',
            self::ST_APPROVED          => 'badge-success',
            self::ST_REJECTED          => 'badge-danger',
            default                    => 'badge-light',
        };
    }

    /** Kelas badge progres pemasangan */
    public function getProgressBadgeClassAttribute(): string
    {
        return match ($this->progress_status) {
            self::PG_QUEUED     => 'badge-secondary',
            self::PG_SURVEY     => 'badge-info',
            self::PG_MATERIAL   => 'badge-primary',
            self::PG_SCHEDULED  => 'badge-warning',
            self::PG_INSTALLING => 'badge-dark',
            self::PG_INSTALLED  => 'badge-success',
            default             => 'badge-light',
        };
    }

    /** Boleh buat SPKO? (APPROVED & belum ada SPKO) */
    public function getBolehBuatSpkoAttribute(): bool
    {
        return $this->status === self::ST_APPROVED && !$this->spko;
    }

    /* =========================
     |  STATUS HELPERS (opsional, tapi rapi)
     * ========================= */
    public function kirimKeDirektur(?string $catatanAdmin = null): void
    {
        $this->update([
            'status'              => self::ST_SENT_TO_DIRECTOR,
            'catatan_admin'       => $catatanAdmin ?? $this->catatan_admin,
            'sent_to_director_at' => now(),
        ]);
    }

    public function setujuiOlehDirektur(?string $catatanDirektur = null, ?int $userId = null): void
    {
        $this->update([
            'status'           => self::ST_APPROVED,
            'catatan_direktur' => $catatanDirektur ?? $this->catatan_direktur,
            'approved_at'      => now(),
            'approved_by'      => $userId,
        ]);
    }

    public function tolakOlehDirektur(string $alasan, ?int $userId = null): void
    {
        $this->update([
            'status'           => self::ST_REJECTED,
            'catatan_direktur' => $alasan,
            'rejected_at'      => now(),
            'rejected_by'      => $userId,
        ]);
    }

    /* =========================
     |  ENUM LISTS (untuk validasi/form)
     * ========================= */
    public static function approvalStatuses(): array
    {
        return [
            self::ST_SUBMITTED,
            self::ST_SENT_TO_DIRECTOR,
            self::ST_APPROVED,
            self::ST_REJECTED,
        ];
    }

    public static function progressStatuses(): array
    {
        return [
            self::PG_QUEUED,
            self::PG_SURVEY,
            self::PG_MATERIAL,
            self::PG_SCHEDULED,
            self::PG_INSTALLING,
            self::PG_INSTALLED,
        ];
    }

    /* =========================
     |  BOOT: generator no_pendaftaran
     * ========================= */
    /**
     * Generate nomor pendaftaran otomatis saat create.
     * Format: PD-YYYYMM-#### (reset tiap bulan)
     */
    protected static function booted()
    {
        static::creating(function (Pengajuan $pengajuan) {
            // set default awal
            $pengajuan->status          ??= self::ST_SUBMITTED;
            $pengajuan->progress_status ??= self::PG_QUEUED;

            // kalau no_pendaftaran sudah di-set manual, lewati
            if (!empty($pengajuan->no_pendaftaran)) return;

            $prefix = 'PD-' . date('Ym') . '-'; // contoh: PD-202511-

            // Ambil record terakhir dengan prefix yang sama
            $last = static::where('no_pendaftaran', 'like', $prefix . '%')
                ->orderByDesc('no_pendaftaran')
                ->first();

            $next = 1;
            if ($last && preg_match('/(\d{4})$/', $last->no_pendaftaran, $m)) {
                $next = (int)$m[1] + 1;
            }

            $pengajuan->no_pendaftaran = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }

        /** Label status persetujuan (Bahasa Indonesia) */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::ST_SUBMITTED         => 'Diajukan',
            self::ST_SENT_TO_DIRECTOR  => 'Dikirim ke Direktur',
            self::ST_APPROVED          => 'Disetujui',
            self::ST_REJECTED          => 'Ditolak',
            default                    => 'Tidak Diketahui',
        };
    }

    /** Label progres pemasangan (Bahasa Indonesia) */
    public function getProgressLabelAttribute(): string
    {
        return match ($this->progress_status) {
            self::PG_QUEUED     => 'Antrian',
            self::PG_SURVEY     => 'Survei Lapangan',
            self::PG_MATERIAL   => 'Material Siap',
            self::PG_SCHEDULED  => 'Dijadwalkan',
            self::PG_INSTALLING => 'Sedang Pemasangan',
            self::PG_INSTALLED  => 'Selesai Pemasangan',
            default             => 'Belum Ada',
        };
    }

}
