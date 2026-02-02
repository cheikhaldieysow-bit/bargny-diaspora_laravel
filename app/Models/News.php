<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'is_published',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get the user that created the news.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include published news.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at');
    }

    /**
     * Scope a query to only include draft news.
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope a query to order by latest published.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Check if the news is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published && !is_null($this->published_at);
    }

    /**
     * Publish the news.
     */
    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish the news.
     */
    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
        ]);
    }

    /**
     * Get excerpt from content.
     */
    public function getExcerptAttribute(): string
    {
        return substr(strip_tags($this->content), 0, 150) . '...';
    }
}
