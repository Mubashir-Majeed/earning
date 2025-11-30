<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Get icon based on notification type.
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'deposit' => 'fa-wallet',
            'withdrawal' => 'fa-money-bill-wave',
            'earnings' => 'fa-dollar-sign',
            'referral' => 'fa-user-plus',
            'admin' => 'fa-shield-alt',
            'package' => 'fa-gem',
            'level' => 'fa-trophy',
            default => 'fa-bell',
        };
    }

    /**
     * Get color based on notification type.
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'deposit' => 'blue',
            'withdrawal' => 'green',
            'earnings' => 'green',
            'referral' => 'purple',
            'admin' => 'red',
            'package' => 'yellow',
            'level' => 'orange',
            default => 'gray',
        };
    }
}
