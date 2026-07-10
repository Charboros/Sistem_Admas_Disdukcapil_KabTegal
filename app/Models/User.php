<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // =========================================================
    // 1. Fungsi Helper Hak Akses (Role)
    // =========================================================
    // Mempermudah kita mengecek tipe user tanpa menulis 'role == admin' berulang kali

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKabid(): bool
    {
        return $this->role === 'kabid';
    }

    // =========================================================
    // 2. Relasi Antar Tabel (Relationships)
    // =========================================================

    // Menghubungkan user ke tabel Aduan
    // 1 User bisa menginput banyak Aduan (hasMany)
    public function aduans()
    {
        return $this->hasMany(Aduan::class, 'created_by');
    }

    // Menghubungkan user ke tabel ResponAduan
    // 1 User bisa memberikan banyak tanggapan/respon (hasMany)
    public function responAduans()
    {
        return $this->hasMany(ResponAduan::class, 'respon_by');
    }
}
