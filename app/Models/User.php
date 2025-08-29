<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization_id',
        'verification_type',
        'verification_image',
        'id_card_front',
        'id_card_back', 
        'verification_id_number',
        'password',
        'approved',
        'password_changed',
        'membership_id',
        'membership_code',
        'rejected',
        'country_of_residence',
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
            'approved' => 'boolean',
            'password_changed' => 'boolean',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getVerificationImageUrlAttribute()
    {
        return $this->verification_image
            ? asset('storage/' . $this->verification_image)
            : null;
    }

    public function getIdCardFrontUrlAttribute()
    {
        return $this->id_card_front
            ? asset('storage/' . $this->id_card_front)
            : null;
    }

    public function getIdCardBackUrlAttribute()
    {
        return $this->id_card_back
            ? asset('storage/' . $this->id_card_back)
            : null;
    }

    public function rejections()
    {
        return $this->hasMany(MemberRejection::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Generate the next membership code for the given org & category.
     * Format: {ORG_PREFIX}{NNNNN}{CATEGORY_PREFIX}, where NNNNN starts at 10001.
     */
    public static function nextMembershipCode(Organization $org, Membership $membership): string
    {
        $orgPrefix = trim((string) $org->org_prefix) ?: 'ORG';
        $catPrefix = trim((string) $membership->prefix) ?: 'CAT';

        // Find the last code that matches ORG%CAT for this organization
        $lastCode = static::query()
            ->where('organization_id', $org->id)
            ->whereNotNull('membership_code')
            ->where('membership_code', 'like', $orgPrefix . '%' . $catPrefix)
            ->orderByDesc('id')
            ->value('membership_code');

        $base = 1001;
        $next = $base;

        if ($lastCode) {
            // Extract the middle number between org and category prefixes
            $pattern = '/^' . preg_quote($orgPrefix, '/') . '(\d+)' . preg_quote($catPrefix, '/') . '$/';

            if (preg_match($pattern, $lastCode, $m)) {
                $next = max($base, ((int) $m[1]) + 1);
            }
        }

        return $orgPrefix . $next . $catPrefix;
    }
}
