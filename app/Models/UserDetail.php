<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_line1',
        'address_line2',
        'city',
        'zipcode',
        'blood_group',
        'norka_registration_number',
        'permanent_home_address',
        'insurance_provider',
        'policy_number',
        'expiry_date',
        'amount',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
