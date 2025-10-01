<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'assigned_date',
        'is_completed',
        'completed_at',
        'points_earned',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'points_earned' => 'integer',
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

    public function earnings()
    {
        return $this->hasMany(UserEarning::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('assigned_date', $date);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
