<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    //
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function tags()
    {
        return $this->belongsTo(Tag::class);
    }
}
