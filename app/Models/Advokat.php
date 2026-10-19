<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advokat extends Model
{
    protected $fillable = [
        'nama',
        'foto',
        'spesialisasi',
        'bio',
        'kontak',
    ];

    public function penugasans(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'advokat_id');
    }
}
