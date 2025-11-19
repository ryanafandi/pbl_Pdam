<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Survei extends Model
{
    use HasFactory;

    protected $table = 'survei';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'spko_id',
        // Jadwal & petugas
        'scheduled_at',
        'petugas_nama',
        'petugas_nipp',
        // Lokasi & dokumentasi
        'latitude',
        'longitude',
        'lokasi_foto',
        // Data teknis
        'jenis_pipa_dinas',
        'panjang_pipa_dinas',
        'jenis_pipa_persil',
        'panjang_pipa_persil',
        'jenis_sambungan',
        'jenis_meter_air',
        'jenis_tanah',
        'kondisi_jalan',
        'kedalaman_galian',
        'kendala_lapangan',
        'catatan_teknis',
        // Validasi
        'disetujui_oleh',
        'disetujui_at',
        'done_at',
          'terobos' // <—
    ];

    protected $casts = [
        'scheduled_at'        => 'datetime',
        'disetujui_at'        => 'datetime',
        'done_at'             => 'datetime',
        'latitude'            => 'decimal:6',
        'longitude'           => 'decimal:6',
        'panjang_pipa_dinas'  => 'decimal:2',
        'panjang_pipa_pensil' => 'decimal:2',
        'kedalaman_galian'    => 'decimal:2',
         'terobos' => 'boolean', // <— Supaya 0/1 jadi false/true otomatis
    ];

    // Relasi
    public function spko()
    {
        return $this->belongsTo(Spko::class, 'spko_id', 'id');
    }

    // Helper URL foto (opsional)
    public function getFotoUrlAttribute(): ?string
    {
        return $this->lokasi_foto ? url('public/survei/' . $this->lokasi_foto) : null;
    }

    // public function scopeSelesai(Builder $query)
    // {
    //     return $query->whereNotNull('done_at');
    // }

    // public function scopeBelum(Builder $query)
    // {
    //     return $query->whereNull('done_at');
    // }
}
