<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'category',
        'thumbnail_url',
        'duration',
        'points_value',
        'is_active',
        'assigned_date',
        'max_watches_per_day',
    ];

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'points_value' => 'integer',
            'is_active' => 'boolean',
            'assigned_date' => 'date',
            'max_watches_per_day' => 'integer',
        ];
    }

    // Relationships
    public function videoTasks()
    {
        return $this->hasMany(VideoTask::class);
    }

    public function videoWatches()
    {
        return $this->hasMany(VideoWatch::class);
    }

    // Helper methods
    public static function extractYoutubeId(?string $url): ?string
    {
        if (!$url) return null;
        
        // youtu.be/<id>
        if (preg_match('~youtu\.be/([a-zA-Z0-9_-]{11})~i', $url, $m)) {
            return $m[1];
        }
        // youtube.com/watch?v=<id>
        if (preg_match('~[?&]v=([a-zA-Z0-9_-]{11})~i', $url, $m)) {
            return $m[1];
        }
        // youtube.com/embed/<id>
        if (preg_match('~embed/([a-zA-Z0-9_-]{11})~i', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return null;
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getYouTubeEmbedUrlAttribute()
    {
        $id = $this->youtube_id ?: self::extractYoutubeId($this->youtube_url);
        // Use YouTube privacy-enhanced (nocookie) domain
        return $id ? "https://www.youtube-nocookie.com/embed/{$id}?rel=0&modestbranding=1&showinfo=0" : null;
    }

    public function getResolvedThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_url) return $this->thumbnail_url;
        $id = $this->youtube_id ?: self::extractYoutubeId($this->youtube_url);
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
