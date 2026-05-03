<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * TenantModel
 *
 * Base model for all tenant-aware models. Automatically filters by current tenant.
 */
class TenantModel extends Model
{
    /**
     * Get the current tenant ID from context
     */
    public static function getTenantId(): ?int
    {
        return tenant('id') ?? null;
    }

    /**
     * Boot the model - attach global scope for tenant filtering
     */
    protected static function booted()
    {
        parent::booted();

        // Auto-scope to current tenant for queries
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (tenant('id')) {
                $builder->where('tenant_id', tenant('id'));
            }
        });

        // Set tenant_id on new models
        static::creating(function ($model) {
            if (!isset($model->tenant_id) && tenant('id')) {
                $model->tenant_id = tenant('id');
            }
        });
    }

    /**
     * Get the tenant that owns this model
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
