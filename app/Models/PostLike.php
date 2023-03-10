<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
