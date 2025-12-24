<?php

/*
===============================================================================
🔐 AUTENTIFIKASI - User Model
===============================================================================
Model untuk user dengan fitur autentifikasi lengkap:

1. HasFactory → Factory untuk testing/seeding
2. Notifiable → Untuk kirim notifikasi (email, SMS, dll)
3. HasApiTokens → Laravel Sanctum untuk API authentication (Web Service)

Password otomatis di-hash menggunakan bcrypt.
Support dual authentication:
- Session-based (web browser)
- Token-based (API/Web Service)
===============================================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nim',      
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // 1 user punya banyak task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
