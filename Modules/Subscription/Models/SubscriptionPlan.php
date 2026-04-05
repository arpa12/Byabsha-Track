<?php

namespace Modules\Subscription\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'price', 'billing_cycle', 'description',
        'max_shops', 'max_branches', 'max_brands', 'max_categories', 'max_sales',
        'has_capital', 'has_restock', 'has_reports', 'has_priority_support',
        'is_active', 'sort_order', 'is_trial', 'trial_days',
    ];

    protected $casts = [
        'has_capital'           => 'boolean',
        'has_restock'           => 'boolean',
        'has_reports'           => 'boolean',
        'has_priority_support'  => 'boolean',
        'is_active'             => 'boolean',
        'is_trial'              => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isFree(): bool
    {
        return $this->slug === 'free' || $this->price == 0;
    }

    public function isTrial(): bool
    {
        return (bool) $this->is_trial;
    }

    public function isUnlimited(string $feature): bool
    {
        return $this->{"max_{$feature}"} === null;
    }

    public static function freePlan(): self
    {
        return static::where('slug', 'free')->firstOrFail();
    }

    public function formattedPrice(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        return '' . number_format($this->price) . '/' . $this->billing_cycle;
    }
}
