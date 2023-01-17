<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserComment extends Model
{
    //
    protected $fillable = [
        'user_id', 'comment_id'
    ];

    public function postComment()
    {
        return $this->belongsTo(PostComment::class);
    }

    public function findUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select(['id', 'name', 'country', 'image']);
    }

}
