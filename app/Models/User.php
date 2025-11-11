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
        'investment_package',
        'pending_deposit_amount',
        'pending_package_code',
        'bep20_address',
        'wallet_bound_at',
        'channel_subscribed_at',
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
            'has_deposited' => 'boolean',
            'is_active' => 'boolean',
            'initial_deposit_amount' => 'decimal:2',
            'pending_deposit_amount' => 'decimal:2',
            'monthly_withdrawals_period' => 'date',
            'unwithdrawable_balance_min' => 'decimal:2',
            'wallet_bound_at' => 'datetime',
            'channel_subscribed_at' => 'datetime',
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

    /**
     * Get the actual count of referrals who have deposited
     * This ensures accurate count based on actual referrals with deposits
     */
    public function getActualReferralsCountAttribute(): int
    {
        return $this->referrals()
            ->whereHas('deposits', function ($query) {
                $query->where('status', 'completed');
            })
            ->count();
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

    public function hasBoundWallet(): bool
    {
        return !empty($this->bep20_address);
    }

    public function hasSubscribedChannel(): bool
    {
        return !is_null($this->channel_subscribed_at);
    }

    public function referralCountsByPackage(): array
    {
        $packages = array_keys(config('investment.packages', []));
        $counts = array_fill_keys($packages, 0);

        $referralPackages = $this->referrals()
            ->where('has_deposited', true)
            ->whereNotNull('investment_package')
            ->pluck('investment_package');

        foreach ($referralPackages as $packageCode) {
            if (isset($counts[$packageCode])) {
                $counts[$packageCode]++;
            }
        }

        return $counts;
    }

    public function referralRequirementRules(): array
    {
        if (!$this->investment_package) {
            return [];
        }

        return config('investment.referral_rules.' . $this->investment_package, []);
    }

    public function referralProgress(): array
    {
        $rules = $this->referralRequirementRules();
        $counts = $this->referralCountsByPackage();

        return collect($rules)->map(function (array $rule) use ($counts) {
            $package = $rule['package'];
            $required = (int) ($rule['count'] ?? 0);
            $current = $counts[$package] ?? 0;

            return [
                'package' => $package,
                'required' => $required,
                'current' => $current,
                'remaining' => max(0, $required - $current),
                'description' => $rule['description'] ?? '',
                'is_alternative' => (bool) ($rule['is_alternative'] ?? false),
            ];
        })->toArray();
    }

    public function scopeWithoutAdmins($query)
    {
        return $query->whereDoesntHave('roles', function ($roleQuery) {
            $roleQuery->where('name', 'admin');
        });
    }

    public function withdrawableProfit(): float
    {
        $balance = (float) ($this->balance ?? 0);
        $locked = (float) ($this->unwithdrawable_balance_min ?? 0);
        return max(0.0, $balance - $locked);
    }

    public function maxWithdrawableForPackage(): float
    {
        $package = $this->investment_package ? config('investment.packages.' . $this->investment_package) : null;
        $cap = $package['withdrawal_cap'] ?? $this->withdrawableProfit();
        return min($this->withdrawableProfit(), (float) $cap);
    }

    public function meetsReferralRequirementForWithdrawal(): bool
    {
        if (!$this->has_deposited || !$this->investment_package) {
            return false;
        }

        $rules = $this->referralRequirementRules();
        if (empty($rules)) {
            return true;
        }

        $counts = $this->referralCountsByPackage();

        foreach ($rules as $rule) {
            $packageCode = $rule['package'] ?? null;
            $required = (int) ($rule['count'] ?? 0);

            if (!$packageCode || $required <= 0) {
                continue;
            }

            $current = $counts[$packageCode] ?? 0;

            if ($current >= $required) {
                return true;
            }
        }

        return false;
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

    /**
     * Check if user meets Level 2 requirements
     * Requirements:
     * - Total referrals: 9
     * - Wallet balance: >= 350
     * - Pro package referrals: 4
     * - Growth package referrals: 2
     * - Starter package referrals: 3
     */
    public function meetsLevel2Requirements(): array
    {
        $referralCounts = $this->referralCountsByPackage();
        $totalReferrals = $this->referrals()->where('has_deposited', true)->count();
        
        $requirements = [
            'total_referrals' => [
                'required' => 9,
                'current' => $totalReferrals,
                'met' => $totalReferrals >= 9,
            ],
            'wallet_balance' => [
                'required' => 350,
                'current' => (float) $this->balance,
                'met' => (float) $this->balance >= 350,
            ],
            'pro_referrals' => [
                'required' => 4,
                'current' => $referralCounts['pro_100'] ?? 0,
                'met' => ($referralCounts['pro_100'] ?? 0) >= 4,
            ],
            'growth_referrals' => [
                'required' => 2,
                'current' => $referralCounts['growth_50'] ?? 0,
                'met' => ($referralCounts['growth_50'] ?? 0) >= 2,
            ],
            'starter_referrals' => [
                'required' => 3,
                'current' => $referralCounts['starter_35'] ?? 0,
                'met' => ($referralCounts['starter_35'] ?? 0) >= 3,
            ],
        ];

        $allMet = collect($requirements)->every(fn($req) => $req['met']);

        return [
            'requirements' => $requirements,
            'all_met' => $allMet,
            'can_upgrade' => $allMet && $this->level === 'level_1',
        ];
    }

    /**
     * Upgrade user to Level 2 if requirements are met
     */
    public function upgradeToLevel2(): bool
    {
        if ($this->level !== 'level_1') {
            return false;
        }

        $requirements = $this->meetsLevel2Requirements();
        
        if ($requirements['all_met']) {
            $this->update(['level' => 'level_2']);
            return true;
        }

        return false;
    }

    public function canWithdraw(): bool
    {
        if (!$this->has_deposited || !$this->investment_package) {
            return false;
        }

        if ($this->withdrawableProfit() < 10) {
            return false;
        }

        if (!$this->hasBoundWallet() || !$this->hasSubscribedChannel()) {
            return false;
        }

        if (!$this->meetsReferralRequirementForWithdrawal()) {
            return false;
        }

        if (!$this->withinMonthlyWithdrawalQuota()) {
            return false;
        }

        return true;
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
