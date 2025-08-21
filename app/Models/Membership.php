<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'organization_id',
        'membership_category_id',
        'name',
        'prefix',
        'unique_id',
        'joining_fee',
        'monthly_fee',
        'status',
        'is_default'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function category()
    {
        return $this->belongsTo(MembershipCategory::class, 'membership_category_id');
    }

    public function rules()
    {
        return $this->hasMany(MembershipRule::class);
    }

    public function benefits()
    {
        return $this->hasMany(MembershipBenefit::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    protected static function booted(): void
    {
        static::creating(function (self $membership) {
            // 1) Ensure prefix is set: use membership.prefix if provided, else organization.prefix, else fallback
            $org = $membership->organization ?? Organization::find($membership->organization_id);
            $orgPrefix = trim((string)($org->org_prefix ?? ''));

            if (!$membership->prefix) {
                $membership->prefix = $orgPrefix ?: 'ORG';
            }

            // 2) Generate unique_id only if not already set
            if (!$membership->unique_id) {
                // We want: ORG_PREFIX + 5-digit number starting from 10001
                // We'll take the MAX(RIGHT(unique_id, 5)) for this org + prefix, then increment

                $base = 10001;

                $maxSuffix = static::where('organization_id', $membership->organization_id)
                    ->where('unique_id', 'like', $membership->prefix . '%')
                    ->selectRaw('MAX(CAST(RIGHT(unique_id, 5) AS UNSIGNED)) as max_suffix')
                    ->value('max_suffix');

                $next = $maxSuffix ? ((int)$maxSuffix + 1) : $base;

                // If you want to enforce always 5 digits, you could pad. Since we start at 10001, it’s already 5+ digits.
                // $suffix = str_pad((string)$next, 5, '0', STR_PAD_LEFT);
                $suffix = (string)$next;

                $membership->unique_id = $membership->prefix . $suffix;
            }
        });
    }
}