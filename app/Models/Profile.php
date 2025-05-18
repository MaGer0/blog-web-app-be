<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'bio',
        'avatar',
        'pronouns',
        'location',
        'github_username'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
