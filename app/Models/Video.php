<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Video extends Model
{
    use HasFactory;

    public const PLATFORM_YOUTUBE = 'youtube';
    public const PLATFORM_TIKTOK = 'tiktok';

    public const PACKAGE_FIELD_MAP = [
        'starter_35' => 'dollar_value_starter',
        'growth_50' => 'dollar_value_growth',
        'pro_100' => 'dollar_value_pro',
    ];

    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'platform',
        'dollar_value',
        'dollar_value_starter',
        'dollar_value_growth',
        'dollar_value_pro',
        'category',
        'thumbnail_url',
        'duration',
        'is_active',
        'assigned_date',
        'max_watches_per_day',
    ];

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'dollar_value' => 'decimal:2',
            'dollar_value_starter' => 'decimal:2',
            'dollar_value_growth' => 'decimal:2',
            'dollar_value_pro' => 'decimal:2',
            'is_active' => 'boolean',
            'assigned_date' => 'date',
            'max_watches_per_day' => 'integer',
            'platform' => 'string',
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
        // youtube.com/shorts/<id>
        if (preg_match('~youtube\.com/shorts/([a-zA-Z0-9_-]{11})~i', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    public static function extractTikTokId(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (preg_match('~tiktok\.com/@[^/]+/video/(\d+)~i', $url, $m)) {
            return $m[1];
        }

        if (preg_match('~tiktok\.com/embed/(?:v2/)?(\d+)~i', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public static function detectVideoSource(?string $url): ?array
    {
        if (!$url) {
            return null;
        }

        if ($youtubeId = self::extractYoutubeId($url)) {
            return [
                'platform' => self::PLATFORM_YOUTUBE,
                'video_id' => $youtubeId,
            ];
        }

        if ($tiktokId = self::extractTikTokId($url)) {
            return [
                'platform' => self::PLATFORM_TIKTOK,
                'video_id' => $tiktokId,
            ];
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

    public function getTikTokEmbedUrlAttribute(): ?string
    {
        if ($this->platform !== self::PLATFORM_TIKTOK) {
            return null;
        }

        $id = $this->youtube_id ?: self::extractTikTokId($this->youtube_url);
        return $id ? "https://www.tiktok.com/embed/v2/{$id}" : null;
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        return $this->platform === self::PLATFORM_TIKTOK
            ? $this->tiktok_embed_url
            : $this->youtube_embed_url;
    }

    public function isYouTube(): bool
    {
        return $this->platform === self::PLATFORM_YOUTUBE;
    }

    public function isTikTok(): bool
    {
        return $this->platform === self::PLATFORM_TIKTOK;
    }

    public function dollarValueForPackage(?string $packageCode): float
    {
        if (!$packageCode) {
            return (float) $this->dollar_value;
        }

        $field = self::PACKAGE_FIELD_MAP[$packageCode] ?? null;
        $value = $field ? $this->{$field} : null;

        return (float) ($value ?? $this->dollar_value);
    }

    public function dollarValueForUser(?User $user): float
    {
        return $this->dollarValueForPackage($user?->investment_package);
    }

    public function getResolvedThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_url) return $this->thumbnail_url;
        if ($this->isTikTok()) {
            return null;
        }
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
