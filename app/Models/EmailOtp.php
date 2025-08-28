<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'email',
        'otp',
        'expires_at',
        'verified_at',
        'attempts',
        'resend_count',
        'last_sent_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];

    public function scopeActive($q)
    {
        return $q->whereNull('verified_at')->where('expires_at', '>', now());
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}