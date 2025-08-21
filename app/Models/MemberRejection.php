<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberRejection extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'rejected_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
