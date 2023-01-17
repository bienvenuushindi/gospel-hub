<?php

namespace App\Models;

use App\Http\Controllers\PostTypeController;
use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{
    //

    public function scopeAudio($query)
    {
        return $query->where('name', '=', 'audio');
    }

    public function scopeArticle($query)
    {
        return $query->where('name', '=', 'article');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function audioPosts(){
        return $this->belongsTo(Post::class,'post_type_id', 'id')->where('post_type_id', '=', PostTypeController::typeAudio());
    }

    public function articlePosts()
    {
        return $this->belongsTo(Post::class,'post_type_id', 'id')->where('post_type_id', '=', PostTypeController::typeArticle());
    }
}
