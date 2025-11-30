<?php

$defaultPackages = [
    'starter_35' => [
        'name' => 'Starter',
        'deposit_amount' => 35.00,
        'withdrawal_cap' => 24.00,
        'description' => 'Ideal for new members who want to experience Earn Quest with a low commitment.',
    ],
    'growth_50' => [
        'name' => 'Growth',
        'deposit_amount' => 50.00,
        'withdrawal_cap' => 36.00,
        'description' => 'Unlock higher earning limits with an accessible mid-tier package.',
    ],
    'pro_100' => [
        'name' => 'Pro',
        'deposit_amount' => 100.00,
        'withdrawal_cap' => 81.00,
        'description' => 'Maximize rewards with the flagship Earn Quest package.',
    ],
];

return [
    'base_packages' => $defaultPackages,
    'packages' => $defaultPackages,

    'referral_rules' => [
        'starter_35' => [
            [
                'package' => 'starter_35',
                'count' => 1,
                'description' => 'Refer one Starter ($35) member to enable withdrawals.',
            ],
        ],
        'growth_50' => [
            [
                'package' => 'starter_35',
                'count' => 2,
                'description' => 'Refer two Starter ($35) members to enable withdrawals.',
            ],
            [
                'package' => 'growth_50',
                'count' => 1,
                'description' => 'Alternatively, refer one Growth ($50) member.',
                'is_alternative' => true,
            ],
        ],
        'pro_100' => [
            [
                'package' => 'pro_100',
                'count' => 1,
                'description' => 'Refer one Pro ($100) member to enable withdrawals.',
            ],
            [
                'package' => 'growth_50',
                'count' => 2,
                'description' => 'Alternatively, refer two Growth ($50) members.',
                'is_alternative' => true,
            ],
            [
                'package' => 'starter_35',
                'count' => 3,
                'description' => 'Alternatively, refer three Starter ($35) members.',
                'is_alternative' => true,
            ],
        ],
    ],
];

