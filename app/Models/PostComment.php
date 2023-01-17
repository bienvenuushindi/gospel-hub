<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{

//    protected $fillable = [
//        'user_id', 'post_id', 'comment', 'user_type', 'parent_id'
//    ];
    //
    protected $guarded = [];

    public function userComment()
    {
        return $this->hasOne(UserComment::class, 'comment_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function visitor()
    {
        return $this->hasMany(VisitorPost::class, 'comment_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

}
