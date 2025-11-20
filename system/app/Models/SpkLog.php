<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkLog extends Model
{
    // Nama tabel di database
    protected $table = 'spk_logs';

    // Kolom yang diizinkan untuk diisi secara massal (create/update)
    protected $fillable = [
        'spk_header_id',  // <--- Wajib ada agar tidak error saat create
        'mulai_pada',
        'selesai_pada',
        'catatan'
    ];

    // Konversi otomatis tipe data
    protected $casts = [
        'spk_header_id' => 'integer',
        'mulai_pada'    => 'datetime',
        'selesai_pada'  => 'datetime',
    ];

    /**
     * Relasi balik ke SPK Header
     */
    public function spk()
    {
        return $this->belongsTo(SpkHeader::class, 'spk_header_id');
    }
}