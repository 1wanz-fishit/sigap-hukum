<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
  protected $fillable = [
    'user_id', 'no_tiket', 'jenis_layanan', 'jenis_bantuan', 'kategori',
    'kronologi', 'dokumen_bukti', 'sumber', 'diinput_oleh', 'status', 'alasan_batal',
];  
public function user()
{
    return $this->belongsTo(User::class);
}

public function diinputOleh()
{
    return $this->belongsTo(User::class, 'diinput_oleh');
}

public function penugasan()
{
    return $this->hasOne(Penugasan::class);
}

public function analisis()
{
    return $this->hasOne(Analisis::class);
}

public function evaluasi()
{
    return $this->hasOne(Evaluasi::class);
}

public function statusLogs()
{
    return $this->hasMany(StatusLog::class);
}

public function dokumentasis()
{
    return $this->hasMany(Dokumentasi::class);
}
}
