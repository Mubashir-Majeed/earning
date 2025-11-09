<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        try {
            if (!Schema::hasTable('settings')) {
                return $default;
            }

            $setting = static::query()->where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return $setting->value ?? $default;
        } catch (QueryException $exception) {
            return $default;
        }
    }

    public static function setValue(string $key, mixed $value): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function apply(?array $basePackages = null): void
    {
        if (!Schema::hasTable('settings')) {
            Config::set('investment.packages', $basePackages ?? Config::get('investment.packages', []));
            return;
        }

        $basePackages = $basePackages ?? Config::get('investment.base_packages', Config::get('investment.packages', []));

        $packageOverrides = static::getValue('packages', null);
        if (is_array($packageOverrides)) {
            Config::set('investment.packages', array_replace_recursive($basePackages, $packageOverrides));
        } else {
            Config::set('investment.packages', $basePackages);
        }

        $siteName = static::getValue('site_name', Config::get('app.name'));
        $siteEmail = static::getValue('site_email', Config::get('mail.from.address', 'admin@earnquest.com'));
        $minWithdrawal = static::getValue('min_withdrawal', Config::get('platform.min_withdrawal', 10));
        $withdrawalFee = static::getValue('withdrawal_fee_percent', Config::get('platform.withdrawal_fee_percent', 5));
        $referralBonus = static::getValue('referral_bonus', Config::get('platform.referral_bonus', 5));
        $videoPointsRate = static::getValue('video_points_rate', Config::get('platform.video_points_rate', 0.1));
        $platformWallet = static::getValue('platform_wallet_address', Config::get('platform.wallet_address'));

        Config::set('app.name', $siteName);
        Config::set('mail.from.address', $siteEmail);
        Config::set('platform.wallet_address', $platformWallet);
        Config::set('platform.min_withdrawal', $minWithdrawal);
        Config::set('platform.withdrawal_fee_percent', $withdrawalFee);
        Config::set('platform.referral_bonus', $referralBonus);
        Config::set('platform.video_points_rate', $videoPointsRate);
    }
}

