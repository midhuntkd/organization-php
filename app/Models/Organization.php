<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\MembershipPlanUpgradeRequest;
use App\Models\User;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo', 'header_logo', 'org_prefix', 'background_image'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected $hidden = [
        'admin_organization',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($org) {
            if (empty($org->slug) && !empty($org->org_prefix)) {
                $org->slug = static::uniqueSlug($org->org_prefix);
            }
        });

        static::updating(function ($org) {
            // Optional: regenerate slug if org_prefix changed and slug not explicitly set
            if ($org->isDirty('org_prefix') && !$org->isDirty('slug')) {
                $org->slug = static::uniqueSlug($org->org_prefix, $org->id);
            }
        });
    }

    protected static function uniqueSlug(string $prefix, ?int $ignoreId = null): string
    {
        $base = Str::slug($prefix);
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // Accessors for full URLs (handles storage disk)
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            $path = 'storage/' . $this->logo;
            $rootPath = Storage::disk('public')->path($this->logo);
            if (file_exists($rootPath)) {
                return asset($path);
            }
        }
        return asset('storage/org/l-logo.png');
    }

    public function getHeaderLogoUrlAttribute(): string
    {
        if ($this->header_logo) {
            $path = 'storage/' . $this->header_logo;
            $rootPath = Storage::disk('public')->path($this->header_logo);
            if (file_exists($rootPath)) {
                return asset($path);
            }
        }

        return $this->logo_url ?? asset('hyper/images/l-logo-ico.png');
    }

    public function getBackgroundImageUrlAttribute(): string
    {
        if ($this->background_image) {
            $path = 'storage/' . $this->background_image;
            $rootPath = Storage::disk('public')->path($this->background_image);
            if (file_exists($rootPath)) {
                return asset($path);
            }
        }
        return asset('hyper/images/auth-bg/bg-16.jpg');
    }

    // Define the route key name for model binding
    // This allows using the slug instead of the ID in routes
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Throw if not found */
    public static function fromSlugOrFail(string $slug): self
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function upgradeRequests()
    {
        return $this->hasManyThrough(
            MembershipPlanUpgradeRequest::class,
            User::class,
            'organization_id', // Foreign key on users table...
            'user_id',         // Foreign key on membership_plan_upgrade_requests table...
            'id',              // Local key on organizations table...
            'id'               // Local key on users table...
        );
    }

    public function ledgers()
    {
        return $this->hasMany(MembershipPaymentLedger::class);
    }

    protected static function booted()
    {
        static::deleting(function ($organization) {
            $organization->users()
                ->whereHas('roles', fn($q) => $q->where('name', 'organization-admin'))
                ->delete();
        });
    }
}
