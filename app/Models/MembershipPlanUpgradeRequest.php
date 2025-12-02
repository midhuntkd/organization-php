<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipPlanUpgradeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_membership_id',
        'membership_id',
        'status',
        'reject_reason',
        'approved_by',
        'rejected_by',
        'rejected_at',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function currentMembership()
    {
        return $this->belongsTo(Membership::class, 'current_membership_id');
    }
}
