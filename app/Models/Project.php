<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'problem',
        'objectif',
        'budget',
        'duration',
        'status',
        'submitted_at',
        'funded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'funded_at' => 'datetime',
            'budget' => 'decimal:2',
            'duration' => 'integer',
        ];
    }

    /**
     * Get the user that owns the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the documents for the project.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Scope a query to only include projects with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include approved projects.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include funded projects.
     */
    public function scopeFunded($query)
    {
        return $query->whereNotNull('funded_at');
    }

    /**
     * Check if the project is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the project is funded.
     */
    public function isFunded(): bool
    {
        return !is_null($this->funded_at);
    }

    /**
     * Mark the project as submitted.
     */
    public function markAsSubmitted(): void
    {
        $this->update([
            'submitted_at' => now(),
            'status' => 'pending',
        ]);
    }

    /**
     * Mark the project as funded.
     */
    public function markAsFunded(): void
    {
        $this->update([
            'funded_at' => now(),
            'status' => 'in_progress',
        ]);
    }
<<<<<<< HEAD
=======


    /**
     * Un projet peut être supprimé uniquement s'il n'est pas financé.
     */
    public function canBeDeleted(): bool
    {
        return !$this->isFunded();
    }
>>>>>>> df3d086 (Delete a project)
}
