<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    protected $fillable = ['pengajuan_id', 'hasil_evaluasi', 'tindak_lanjut', 'dibuat_oleh'];
    public function pengajuan()
{
    return $this->belongsTo(Pengajuan::class);
}

public function pembuat()
{
    return $this->belongsTo(User::class, 'dibuat_oleh');
}
}
