<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penugasan extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'advokat_id',
        'tanggal_mulai',
        'catatan',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function advokat(): BelongsTo
    {
        return $this->belongsTo(Advokat::class, 'advokat_id');
    }
}
