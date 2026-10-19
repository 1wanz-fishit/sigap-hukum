<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
    'nama',
    'email',
    'password',
    'role',
    'tipe_pelapor',
    'nim_nip',
    'sso_id',
];
public function pengajuans()
{
    return $this->hasMany(Pengajuan::class);
}

public function isPelapor(): bool
{
    return $this->role === 'pelapor';
}

public function isAdmin(): bool
{
    return in_array($this->role, ['admin_pengurus', 'admin_relawan']);
}

public function isSuperAdmin(): bool
{
    return $this->role === 'super_admin';
}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
