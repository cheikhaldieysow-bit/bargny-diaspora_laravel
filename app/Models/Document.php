<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'type',
        'filename',
    ];

    /**
     * Get the project that owns the document.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the full path to the document.
     */
    public function getFullPathAttribute(): string
    {
        return Storage::url('documents/' . $this->filename);
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if the document is an image.
     */
    public function isImage(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        return in_array(strtolower($this->extension), $imageExtensions);
    }

    /**
     * Check if the document is a PDF.
     */
    public function isPdf(): bool
    {
        return strtolower($this->extension) === 'pdf';
    }

    /**
     * Delete the document file from storage.
     */
    public function deleteFile(): bool
    {
        return Storage::delete('documents/' . $this->filename);
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting a document, also delete the file
        static::deleting(function ($document) {
            $document->deleteFile();
        });
    }
}
