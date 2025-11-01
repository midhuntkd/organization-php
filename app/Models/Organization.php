<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo','org_prefix', 'background_image'];

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
            if (empty($org->slug) && !empty($org->name)) {
                $org->slug = static::uniqueSlug($org->name);
            }
        });

        static::updating(function ($org) {
            // Optional: regenerate slug if name changed and slug not explicitly set
            if ($org->isDirty('name') && !$org->isDirty('slug')) {
                $org->slug = static::uniqueSlug($org->name, $org->id);
            }
        });
    }

    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
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

    protected static function booted()
    {
        static::deleting(function ($organization) {
            $organization->users()
                ->whereHas('roles', fn($q) => $q->where('name', 'organization-admin'))
                ->delete();
        });
    }
}
