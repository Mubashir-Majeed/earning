<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_task_id',
        'points_earned',
        'dollar_value',
        'type',
        'description',
        'earned_date',
    ];

    protected function casts(): array
    {
        return [
            'points_earned' => 'integer',
            'dollar_value' => 'decimal:2',
            'earned_date' => 'date',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videoTask()
    {
        return $this->belongsTo(VideoTask::class);
    }

    // Scopes
    public function scopeVideoWatch($query)
    {
        return $query->where('type', 'video_watch');
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('earned_date', $date);
    }
}
