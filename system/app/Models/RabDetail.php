<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabDetail extends Model
{
    protected $table = 'rab_detail';
    public $timestamps = true;

    protected $fillable = [
        'rab_id',
        'kategori',      // 'pipa_dinas' / 'pipa_pensil'
        'uraian',
        'satuan',
        'volume',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'rab_id'       => 'integer',
        'volume'       => 'decimal:3',
        'harga_satuan' => 'decimal:2',
        'jumlah'       => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    /**
     * Detail milik 1 header RAB
     */
    public function rab()
    {
        return $this->belongsTo(RabHeader::class, 'rab_id');
    }

    /**
     * Helper: apakah item ini pipa dinas?
     */
    public function getIsPipaDinasAttribute(): bool
    {
        return $this->kategori === 'pipa_dinas';
    }

    /**
     * Helper: apakah item ini pipa persil?
     */
    public function getIsPipaPersilAttribute(): bool
    {
        return $this->kategori === 'pipa_persil';
    }
}
