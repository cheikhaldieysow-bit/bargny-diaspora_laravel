<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ AJOUT

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ AJOUT

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'address',
        'password',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function contributionPayments()
    {
        return $this->hasMany(ContributionPayment::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }
}
