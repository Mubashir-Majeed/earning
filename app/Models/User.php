<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'points',
        'has_deposited',
        'is_active',
        'level',
        'referrer_id',
        'referral_code',
        'initial_deposit_amount',
        'referrals_count',
        'monthly_withdrawals_count',
        'monthly_withdrawals_period',
        'unwithdrawable_balance_min',
        'phone',
        'payment_method',
        'payment_details',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'points' => 'integer',
            'has_deposited' => 'boolean',
            'is_active' => 'boolean',
            'initial_deposit_amount' => 'decimal:2',
            'monthly_withdrawals_period' => 'date',
            'unwithdrawable_balance_min' => 'decimal:2',
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

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function earnings()
    {
        return $this->hasMany(UserEarning::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    // Helper methods
    public function canAccessTasks()
    {
        return $this->has_deposited && $this->is_active;
    }

    public function requiredDailyVideos(): int
    {
        $level = config('levels.levels.' . $this->level);
        return $level['daily_videos'] ?? 5;
    }

    public function withdrawalMonthlyLimit(): int
    {
        $level = config('levels.levels.' . $this->level);
        return $level['withdrawals_per_month'] ?? 1;
    }

    public function withdrawalFeePercent(): float
    {
        $level = config('levels.levels.' . $this->level);
        return ($level['withdrawal_fee_percent'] ?? 5) / 100.0;
    }

    public function meetsReferralRequirementForWithdrawal(): bool
    {
        if ($this->level === 'level_1') {
            return $this->referrals()->count() >= 1;
        }
        if ($this->level === 'level_2') {
            if ($this->initial_deposit_amount >= 100) {
                return $this->referrals()->count() >= 6;
            }
            if ($this->initial_deposit_amount >= 50) {
                return $this->referrals()->count() >= 12;
            }
        }
        if ($this->level === 'level_3') {
            if ($this->initial_deposit_amount >= 100) {
                return $this->referrals()->count() >= 15;
            }
            if ($this->initial_deposit_amount >= 50) {
                return $this->referrals()->count() >= 25;
            }
        }
        return true;
    }

    public function withinMonthlyWithdrawalQuota(): bool
    {
        $period = $this->monthly_withdrawals_period;
        $nowMonth = now()->startOfMonth();
        if (!$period || $period->lt($nowMonth)) {
            // reset counter for new month
            $this->monthly_withdrawals_period = $nowMonth;
            $this->monthly_withdrawals_count = 0;
            $this->save();
        }
        return $this->monthly_withdrawals_count < $this->withdrawalMonthlyLimit();
    }

    public function incrementMonthlyWithdrawalCount(): void
    {
        $this->monthly_withdrawals_count += 1;
        $this->monthly_withdrawals_period = now()->startOfMonth();
        $this->save();
    }

    public function getTotalEarningsAttribute()
    {
        return $this->earnings()->sum('dollar_value');
    }

    public function canWithdraw(): bool
    {
        return $this->has_deposited && 
               $this->balance >= 10 && 
               $this->meetsReferralRequirementForWithdrawal() && 
               $this->withinMonthlyWithdrawalQuota();
    }

    public function requiredReferralsForWithdrawal(): int
    {
        $config = config('levels.levels.' . $this->level);
        return $config['referrals_required_for_withdrawal'] ?? 0;
    }

    public function getAvailableBalanceAttribute()
    {
        return $this->balance;
    }

    /**
     * Generate a unique referral code
     */
    public static function generateReferralCode(): string
    {
        do {
            // Generate a 6-character code with letters and numbers
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Boot method to auto-generate referral code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateReferralCode();
            }
        });
    }
}
