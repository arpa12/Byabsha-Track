<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const MODULE_ACCESS_KEYS = [
        'dashboard',
        'shop',
        'brand',
        'category',
        'product',
        'stock',
        'sale',
        'capital',
        'restock',
        'report',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'module_access',
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
            'module_access' => 'array',
        ];
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public static function availableModuleAccessKeys(): array
    {
        return self::MODULE_ACCESS_KEYS;
    }

    public function getModuleAccessList(): array
    {
        if ($this->isSuperAdmin()) {
            return self::MODULE_ACCESS_KEYS;
        }

        $configured = is_array($this->module_access) ? $this->module_access : [];

        if (count($configured) === 0) {
            // Backward compatibility for old users before module access was configured.
            return self::MODULE_ACCESS_KEYS;
        }

        return array_values(array_intersect(self::MODULE_ACCESS_KEYS, $configured));
    }

    public function hasModuleAccess(string $moduleKey): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($moduleKey, $this->getModuleAccessList(), true);
    }

    public function homeRouteName(): string
    {
        $moduleRouteMap = [
            'dashboard' => 'dashboard.index',
            'shop' => 'shop.index',
            'brand' => 'brand.index',
            'category' => 'category.index',
            'product' => 'product.index',
            'stock' => 'stock.index',
            'sale' => 'sale.index',
            'capital' => 'capital.index',
            'restock' => 'restock.index',
            'report' => 'report.index',
        ];

        foreach ($this->getModuleAccessList() as $moduleKey) {
            $routeName = $moduleRouteMap[$moduleKey] ?? null;

            if ($routeName && Route::has($routeName)) {
                return $routeName;
            }
        }

        return 'user.profile.edit';
    }

    /**
     * Get the user's notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }
}
