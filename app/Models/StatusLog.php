<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    protected $fillable = ['pengajuan_id', 'status', 'keterangan', 'diupdate_oleh'];
    public function pengajuan()
{
    return $this->belongsTo(Pengajuan::class);
}

public function pengupdate()
{
    return $this->belongsTo(User::class, 'diupdate_oleh');
}
}
