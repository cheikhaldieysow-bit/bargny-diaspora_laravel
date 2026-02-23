<?php

//use Laravel\Sanctum\HasApiTokens;

//class User extends Authenticatable
//{
 //   use HasApiTokens, HasFactory, Notifiable;
//}


namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    public const PROVIDER_GOOGLE = 'google';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function projects(): HasMany
    /**
     * Get the projects for the user.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function contributionPayments(): HasMany
    /**
     * Get the contribution payments for the user.
     */
    public function contributionPayments()
    {
        return $this->hasMany(ContributionPayment::class);
    }

    public function news(): HasMany
    /**
     * Get the news created by the user.
     */
    public function news()
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

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isGoogleAccount(): bool
    {
        return $this->provider === self::PROVIDER_GOOGLE && !empty($this->google_id);
    }
}
