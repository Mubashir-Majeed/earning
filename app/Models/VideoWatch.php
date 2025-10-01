<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoWatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'watch_started_at',
        'watch_completed_at',
        'watch_duration',
        'is_completed',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'watch_started_at' => 'datetime',
            'watch_completed_at' => 'datetime',
            'watch_duration' => 'integer',
            'is_completed' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }
}
