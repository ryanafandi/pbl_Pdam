<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkFoto extends Model
{
    // Nama tabel di database
    protected $table = 'spk_fotos';

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'spk_header_id', // <--- Wajib ada
        'foto_path',
        'keterangan'
    ];

    protected $casts = [
        'spk_header_id' => 'integer',
    ];

    /**
     * Relasi balik ke SPK Header
     */
    public function spk()
    {
        return $this->belongsTo(SpkHeader::class, 'spk_header_id');
    }
}