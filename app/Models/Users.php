<?php

namespace App\Models;

use App\Http\Controllers\PostTypeController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected  $table='users';
    //
//    protected  $fillable=[
//        'name','blocked','user_type_id','country','image','ministry_church','phone','contact_visibility', 'email', 'password'
//    ];
//'title', 'img', 'published','description','short_description','teaching_id','user_id','post_type_id','duration'
    public function posts(PostType $postType=null)
    {
        if($postType != null) return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=',$postType->id);
        else return $this->hasMany(Post::class);
    }

    public function postsId(){
        return $this->hasMany(Post::class,'user_id', 'id')->select('id');
    }
    public function authorPosts()
    {
        return $this->hasMany(Post::class,'user_id', 'id')->select(['title', 'img', 'short_description', 'teaching_id', 'post_type_id', 'duration']);
    }
    public function audioPosts(){
        return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=', PostTypeController::typeAudio());
    }

    public function articlePosts()
    {
        return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=', PostTypeController::typeArticle());
    }

    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function postViews()
    {
        return $this->hasMany(PostView::class);
    }

    public function comments()
    {
        return $this->hasMany(UserComment::class);
    }

    public function teachings()
    {
        return $this->hasMany(Teaching::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function userEvents()
    {
        return $this->hasMany(\Event::class);
    }
}
