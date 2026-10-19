<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $fillable = ['pengajuan_id', 'judul', 'jenis', 'file', 'diunggah_oleh'];
    public function pengajuan()
{
    return $this->belongsTo(Pengajuan::class);
}

public function pengunggah()
{
    return $this->belongsTo(User::class, 'diunggah_oleh');
}
}
