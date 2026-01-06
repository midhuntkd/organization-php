<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPaymentLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_id',
        'membership_id',
        'entry_type',
        'reason',
        'amount',
        'payment_method',
        'payment_date',
        'handover_person',
        'proof_path',
        'description',
        'status',
        'approved_by',
        'rejected_by',
        'rejected_reason',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'organization_id' => 'integer',
        'user_id' => 'integer',
        'membership_id' => 'integer',
        'approved_by' => 'integer',
        'rejected_by' => 'integer',
        'amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
