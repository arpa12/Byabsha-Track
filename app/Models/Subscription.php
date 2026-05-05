<?php

namespace App\Models;

class Subscription
{
    /**
     * Return available plans as simple arrays for the admin UI.
     * Premium includes all features; Basic and Standard have limited features.
     *
     * @return array
     */
    public static function plans(): array
    {
        $features = [
            'Analytics',
            'Multi-branch',
            'Inventory Alerts',
            'Priority Support',
            'Custom Reports',
        ];

        return [
            [
                'key' => 'basic',
                'name' => 'Basic',
                'price' => 'Free',
                'description' => 'A starter plan with very limited features.',
                'features' => [
                    'Analytics' => false,
                    'Multi-branch' => false,
                    'Inventory Alerts' => true,
                    'Priority Support' => false,
                    'Custom Reports' => false,
                ],
            ],
            [
                'key' => 'standard',
                'name' => 'Standard',
                'price' => '$19 / mo',
                'description' => 'For growing shops — most common features enabled.',
                'features' => [
                    'Analytics' => true,
                    'Multi-branch' => true,
                    'Inventory Alerts' => true,
                    'Priority Support' => false,
                    'Custom Reports' => false,
                ],
            ],
            [
                'key' => 'premium',
                'name' => 'Premium',
                'price' => '$49 / mo',
                'description' => 'All features enabled plus premium support.',
                'features' => array_fill_keys($features, true),
            ],
        ];
    }
}
