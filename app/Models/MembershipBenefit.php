<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipBenefit extends Model
{
    protected $fillable = ['membership_id', 'title', 'description'];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
