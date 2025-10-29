<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    function handleUploadfoto()
    {
        if (request()->hasFile('foto_rumah')) {
            $foto_rumah = request()->file('foto_rumah');
            $destination = "pengajuan";
            $randomStr = Str::random(5);
            $filename = time() . "-"  . $randomStr . "."  . $foto_rumah->extension();
            $url = $foto_rumah->storeAs($destination, $filename);
            $this->foto_rumah = "app/" . $url;
            $this->save();
        }
    }
}
