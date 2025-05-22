<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_id',
        'user_id',
        'content',
        'helpful_votes',
    ];

    /**
     * Get the forum that the reply belongs to.
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Get the user that created the reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
