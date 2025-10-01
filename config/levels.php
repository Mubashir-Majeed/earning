<?php

return [
    'points_to_dollars' => [
        'points' => 750,
        'dollars' => 80.0,
    ],

    'levels' => [
        'level_1' => [
            'required_deposit' => 100,
            'required_referrals_for_withdrawal' => 1,
            'max_earnings' => 80,
            'daily_videos' => 5,
            'withdrawals_per_month' => 1,
            'unwithdrawable_balance_min' => 50,
            'withdrawal_fee_percent' => 5,
        ],
        'level_2' => [
            'required_deposit' => 300,
            'required_referrals_if_new_deposit_100' => 6,
            'required_referrals_if_new_deposit_50' => 12,
            'max_earnings' => 200,
            'daily_videos' => 10,
            'withdrawals_per_month' => 2,
            'unwithdrawable_balance_min' => 50,
            'withdrawal_fee_percent' => 5,
        ],
        'level_3' => [
            'required_deposit' => 900,
            'required_referrals_if_new_deposit_100' => 15,
            'required_referrals_if_new_deposit_50' => 25,
            'max_earnings' => 600,
            'daily_videos' => 15,
            'withdrawals_per_month' => 3,
            'unwithdrawable_balance_min' => 50,
            'withdrawal_fee_percent' => 5,
            'locked' => true,
        ],
    ],
];


