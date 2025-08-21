<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipCategory extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'prefix',
        'description',
        'status',
        'is_default'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }
}
