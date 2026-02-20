<?php

//use Laravel\Sanctum\HasApiTokens;

//class User extends Authenticatable
//{
 //   use HasApiTokens, HasFactory, Notifiable;
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    public const PROVIDER_GOOGLE = 'google';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'address',
        'phone',
        'password',

        // Google
        'google_id',
        'provider',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $with = ['role'];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* --------------------
     | Relations
     -------------------- */

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function contributionPayments(): HasMany
    {
        return $this->hasMany(ContributionPayment::class);
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /* --------------------
     | Scopes
     -------------------- */

    public function scopeAdmins($query)
    {
        return $query->whereHas('role', fn ($q) => $q->where('name', 'Admin'));
    }

    public function scopeByProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    /* --------------------
     | Helpers
     -------------------- */

    public function hasRole(string $roleName): bool
    {
        return $this->role?->name === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isGoogleAccount(): bool
    {
        return $this->provider === self::PROVIDER_GOOGLE && !empty($this->google_id);
    }
}
