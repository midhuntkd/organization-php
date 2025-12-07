<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipRule extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'title', 'description'];

    protected $casts = [
        'organization_id' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function memberships()
    {
        return $this->belongsToMany(Membership::class, 'membership_rule_membership');
    }
}
