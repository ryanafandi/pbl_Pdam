<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabHeader extends Model
{
    // Karena nama tabel tidak mengikuti bentuk jamak Laravel
    protected $table = 'rab_header';

    // Primary key default "id" sudah benar, jadi tidak perlu diubah
    // public $primaryKey = 'id';

    // Jika pakai timestamps (created_at, updated_at) biarkan true
    public $timestamps = true;


    public const BILL_DRAFT = 'DRAFT';
    public const BILL_SENT  = 'SENT';
    public const BILL_PAID  = 'PAID';
    /**
     * Field yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'spko_id',
        'nomor_rab',
        'nama_pelanggan',
        'alamat',
        'kategori_tarif',
        'pemasangan_terobos',
        'subtotal_pipa_dinas',
        'subtotal_pipa_persil',
        'biaya_pendaftaran',
        'biaya_admin',
        'total',
        'status',
        'sent_to_director_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_note',

        // --- dokumen biaya / RNA ---
        'rna_nomor',
        'rna_tanggal',
        'persetujuan_nomor',
        'persetujuan_tanggal',
        'jatuh_tempo',
        'billing_status',
        'billing_sent_at',
        'billing_paid_at',
        'billing_note',
    ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'spko_id'              => 'integer',
        'pemasangan_terobos'   => 'boolean', // 0 / 1
        'subtotal_pipa_dinas'  => 'decimal:2',
        'subtotal_pipa_persil' => 'decimal:2',
        'biaya_pendaftaran'    => 'decimal:2',
        'biaya_admin'          => 'decimal:2',
        'total'                => 'decimal:2',

        // tanggal / datetime baru
        'rna_tanggal'          => 'date',
        'persetujuan_tanggal'  => 'date',
        'jatuh_tempo'          => 'date',
        'billing_sent_at'      => 'datetime',
        'billing_paid_at'      => 'datetime',
    ];

    /* ==========================
     *  RELASI
     * ==========================
     */



    /**
     * 1 RAB dimiliki oleh 1 SPKO
     */
    public function spko()
    {
        return $this->belongsTo(Spko::class, 'spko_id', 'id');
    }

    /**
     * 1 RAB punya banyak detail item
     */
    public function details()
    {
        return $this->hasMany(RabDetail::class, 'rab_id', 'id');
    }

    /* ==========================
     *  ACCESSOR / HELPER KECIL
     * ==========================
     */

    /**
     * Label manis untuk pemasangan_terobos (Ya / Tidak)
     */
    public function getPemasanganTerobosLabelAttribute(): string
    {
        return $this->pemasangan_terobos ? 'Ya' : 'Tidak';
    }

    public function getBillingStatusLabelAttribute(): string
    {
        return match ($this->billing_status) {
            self::BILL_SENT => 'Dikirim ke Pelanggan',
            self::BILL_PAID => 'Lunas',
            default         => 'Draft Dokumen',
        };
    }

    public static function generateRnaNumber(): string
    {
        // contoh: RNA-202511-
        $prefix = 'RNA-' . date('Ym') . '-';

        // cari nomor terakhir bulan ini
        $last = static::where('rna_nomor', 'like', $prefix . '%')
            ->orderByDesc('rna_nomor')
            ->first();

        $next = 1;
        if ($last && preg_match('/(\d{4})$/', $last->rna_nomor, $m)) {
            $next = (int)$m[1] + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public static function generatePersetujuanNumber(): string
    {
        $prefix = 'BT-' . date('Ym') . '-';

        $last = static::where('persetujuan_nomor', 'like', $prefix . '%')
            ->orderByDesc('persetujuan_nomor')
            ->first();

        $next = 1;
        if ($last && preg_match('/(\d{4})$/', $last->persetujuan_nomor, $m)) {
            $next = (int)$m[1] + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalTerbilangAttribute(): string
    {
        $angka = (int) round($this->total ?? 0);
        if ($angka === 0) {
            return 'nol rupiah';
        }
        return trim(static::terbilang($angka)) . ' rupiah';
    }

    /**
     * Helper terbilang angka (Indonesia)
     * Contoh: 12500 → "dua belas ribu lima ratus"
     */
    protected static function terbilang(int $angka): string
    {
        $bilangan = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas'
        ];

        if ($angka < 12) {
            return ' ' . $bilangan[$angka];
        } elseif ($angka < 20) {
            return static::terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            return static::terbilang(intval($angka / 10)) . ' puluh' . static::terbilang($angka % 10);
        } elseif ($angka < 200) {
            return ' seratus' . static::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return static::terbilang(intval($angka / 100)) . ' ratus' . static::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return ' seribu' . static::terbilang($angka - 1000);
        } elseif ($angka < 1000000) { // ribuan
            return static::terbilang(intval($angka / 1000)) . ' ribu' . static::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) { // jutaan
            return static::terbilang(intval($angka / 1000000)) . ' juta' . static::terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) { // milyar
            return static::terbilang(intval($angka / 1000000000)) . ' milyar' . static::terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) { // trilyun
            return static::terbilang(intval($angka / 1000000000000)) . ' trilyun' . static::terbilang($angka % 1000000000000);
        } else {
            return '';
        }
    }


    // di dalam class RabHeader

    public function getIsPaidAttribute(): bool
    {
        return $this->billing_status === 'PAID';
    }

    public function getIsSentAttribute(): bool
    {
        return $this->billing_status === 'SENT';
    }

    // di atas / bawah relasi details()
    public function spk()
    {
        return $this->hasOne(SpkHeader::class, 'rab_id', 'id');
    }

    
}
