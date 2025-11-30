<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait CreatesNotifications
{
    /**
     * Create a notification for a user.
     */
    public static function createNotification(User $user, string $type, string $title, string $message, ?string $link = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    /**
     * Create deposit notification.
     */
    public static function notifyDepositApproved(User $user, float $amount, string $packageName): Notification
    {
        return self::createNotification(
            $user,
            'deposit',
            'Deposit Approved',
            "Your deposit of $" . number_format($amount, 2) . " for {$packageName} package has been approved.",
            route('dashboard')
        );
    }

    /**
     * Create deposit rejected notification.
     */
    public static function notifyDepositRejected(User $user, float $amount): Notification
    {
        return self::createNotification(
            $user,
            'deposit',
            'Deposit Rejected',
            "Your deposit of $" . number_format($amount, 2) . " has been rejected. Please contact support.",
            route('deposit')
        );
    }

    /**
     * Create withdrawal approved notification.
     */
    public static function notifyWithdrawalApproved(User $user, float $amount): Notification
    {
        return self::createNotification(
            $user,
            'withdrawal',
            'Withdrawal Approved',
            "Your withdrawal request of $" . number_format($amount, 2) . " has been approved and processed.",
            route('withdrawal.history')
        );
    }

    /**
     * Create withdrawal rejected notification.
     */
    public static function notifyWithdrawalRejected(User $user, float $amount, ?string $reason = null): Notification
    {
        $message = "Your withdrawal request of $" . number_format($amount, 2) . " has been rejected.";
        if ($reason) {
            $message .= " Reason: {$reason}";
        }
        
        return self::createNotification(
            $user,
            'withdrawal',
            'Withdrawal Rejected',
            $message,
            route('withdrawal.history')
        );
    }

    /**
     * Create earnings notification.
     */
    public static function notifyEarning(User $user, float $amount, string $source = 'video'): Notification
    {
        return self::createNotification(
            $user,
            'earnings',
            'New Earnings',
            "You earned $" . number_format($amount, 2) . " from {$source}.",
            route('earnings')
        );
    }

    /**
     * Create referral notification.
     */
    public static function notifyReferral(User $user, string $referralName, string $packageName): Notification
    {
        return self::createNotification(
            $user,
            'referral',
            'New Referral',
            "{$referralName} joined with {$packageName} package using your referral link!",
            route('referrals')
        );
    }

    /**
     * Create admin notification.
     */
    public static function notifyAdmin(User $user, string $title, string $message, ?string $link = null): Notification
    {
        return self::createNotification(
            $user,
            'admin',
            $title,
            $message,
            $link
        );
    }

    /**
     * Create package activation notification.
     */
    public static function notifyPackageActivated(User $user, string $packageName): Notification
    {
        return self::createNotification(
            $user,
            'package',
            'Package Activated',
            "Your {$packageName} package has been successfully activated!",
            route('dashboard')
        );
    }

    /**
     * Create level upgrade notification.
     */
    public static function notifyLevelUpgrade(User $user, string $level): Notification
    {
        return self::createNotification(
            $user,
            'level',
            'Level Upgraded',
            "Congratulations! You have been upgraded to {$level}.",
            route('level')
        );
    }

    /**
     * Notify all admins about a new withdrawal request.
     */
    public static function notifyAdminsOfWithdrawalRequest(User $user, float $amount): void
    {
        $admins = \App\Models\User::role('admin')->get();
        
        foreach ($admins as $admin) {
            self::createNotification(
                $admin,
                'admin',
                'New Withdrawal Request',
                "{$user->name} has requested a withdrawal of $" . number_format($amount, 2) . ".",
                route('admin.withdrawals')
            );
        }
    }

    /**
     * Notify all admins about a new deposit request.
     */
    public static function notifyAdminsOfDepositRequest(User $user, float $amount, string $packageName): void
    {
        $admins = \App\Models\User::role('admin')->get();
        
        foreach ($admins as $admin) {
            self::createNotification(
                $admin,
                'admin',
                'New Deposit Request',
                "{$user->name} has submitted a deposit of $" . number_format($amount, 2) . " for {$packageName} package.",
                route('admin.deposits')
            );
        }
    }
}
