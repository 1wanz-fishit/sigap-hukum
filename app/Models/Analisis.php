<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    protected $table = 'analisis';

protected $fillable = ['pengajuan_id', 'ringkasan_kasus', 'rekomendasi', 'bentuk_bantuan', 'dibuat_oleh'];
public function pengajuan()
{
    return $this->belongsTo(Pengajuan::class);
}

public function pembuat()
{
    return $this->belongsTo(User::class, 'dibuat_oleh');
}
}
