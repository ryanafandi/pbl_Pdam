<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;   // utk scope type-hint
use App\Models\Pengajuan;
use App\Models\Survei;
use App\Models\RabHeader;

class Spko extends Model
{
    use HasFactory;

    protected $table = 'spko';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pengajuan_id',
        'nomor_spko',
        'tanggal_spko',
        'tujuan',
        'pemilik_nama',
        'alamat',
        'lokasi',
        'kepada_jabatan',
        'laporan_ringkas',
        'terobos',
        'disurvey_oleh_nama',
        'disurvey_oleh_nipp',
        'kabag_teknik_nama',
        'kabag_teknik_nipp',
        'direktur_nama',
        'direktur_nipp',
        'status',
        'catatan',
        'sent_to_planning_at',
        'survey_scheduled_at',
        'sent_to_director_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'approval_note',
    ];

    // ====== Status ======
    const ST_DRAFT         = 'DRAFT';
    const ST_SENT_PLANNING = 'SENT_TO_PLANNING';
    const ST_SENT_DIRECTOR = 'SENT_TO_DIRECTOR';
    const ST_APPROVED      = 'APPROVED';
    const ST_REJECTED      = 'REJECTED';
    const ST_DONE          = 'DONE';

    protected $casts = [
        'tanggal_spko'        => 'date',
        'sent_to_planning_at' => 'datetime',
        'survey_scheduled_at' => 'datetime',
        'sent_to_director_at' => 'datetime',
        'approved_at'         => 'datetime',
        'rejected_at'         => 'datetime',
        'terobos'             => 'boolean',
    ];

    /* =========================
     |  RELASI
     * ========================= */
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }

    public function survei()
    {
        return $this->hasOne(Survei::class, 'spko_id', 'id');
    }

    public function rab()
    {
        return $this->hasOne(RabHeader::class, 'spko_id');
    }

    /* =========================
     |  BADGE & LABEL STATUS
     * ========================= */

    // Kelas badge untuk Blade
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::ST_DRAFT         => 'badge-secondary',
            self::ST_SENT_PLANNING => 'badge-info',
            self::ST_SENT_DIRECTOR => 'badge-primary',
            self::ST_APPROVED      => 'badge-success',
            self::ST_REJECTED      => 'badge-danger',
            self::ST_DONE          => 'badge-dark',
            default                => 'badge-light',
        };
    }

    // Label status dalam Bahasa Indonesia
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::ST_DRAFT         => 'Draft',
            self::ST_SENT_PLANNING => 'Dikirim ke Perencanaan',
            self::ST_SENT_DIRECTOR => 'Dikirim ke Direktur',
            self::ST_APPROVED      => 'Disetujui',
            self::ST_REJECTED      => 'Ditolak',
            self::ST_DONE          => 'Selesai',
            default                => 'Tidak Diketahui',
        };
    }

    // (opsional) untuk dropdown filter
    public static function statuses(): array
    {
        return [
            self::ST_DRAFT,
            self::ST_SENT_PLANNING,
            self::ST_SENT_DIRECTOR,
            self::ST_APPROVED,
            self::ST_REJECTED,
            self::ST_DONE,
        ];
    }

    /* =========================
     |  SCOPES & HELPERS
     * ========================= */

    /** Antrian untuk Tim Perencanaan (yang sudah dikirim admin) */
    public function scopeForPerencanaanQueue(Builder $query)
    {
        return $query->where('status', self::ST_SENT_PLANNING);
    }

    public function isDraft(): bool          { return $this->status === self::ST_DRAFT; }
    public function isSentToPlanning(): bool { return $this->status === self::ST_SENT_PLANNING; }

    /* =========================
     |  BOOT: generator nomor SPKO
     * ========================= */

    /** Generator nomor SPKO otomatis: SPKO-YYYYMM-0001 */
    protected static function booted()
    {
        static::creating(function (Spko $spko) {
            if (!empty($spko->nomor_spko)) return;

            $prefix = 'SPKO-'.date('Ym').'-';
            $last = static::where('nomor_spko', 'like', $prefix.'%')
                ->orderByDesc('nomor_spko')->first();

            $next = 1;
            if ($last && preg_match('/(\d{4})$/', $last->nomor_spko, $m)) {
                $next = ((int)$m[1]) + 1;
            }
            $spko->nomor_spko = $prefix.str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }

    public function index()
{
    $spko = Spko::with(['pengajuan', 'survei'])
        // status SENT_TO_PLANNING (isi scope forPerencanaanQueue)
        ->forPerencanaanQueue()
        // dan SUDAH punya jadwal
        ->whereNotNull('survey_scheduled_at')
        ->orderByDesc('survey_scheduled_at')
        ->orderByDesc('id')
        ->paginate(15);

    return view('perencanaan.survei.index', compact('spko'));
}
}
