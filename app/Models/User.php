<?php

namespace App\Models;

use App\Http\Controllers\PostTypeController;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password', 'country', 'type_id', 'blocked', 'image', 'ministry_church', 'ministry_type', 'phone', 'contact_visibility', 'description'
//    ];

     protected $guarded=[];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function posts(PostType $postType=null)
    {
        if($postType != null) return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=',$postType->id);
        else return $this->hasMany(Post::class);
    }

    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function postViews()
    {
        return $this->hasMany(PostView::class);
    }

    public function postComments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function teachings()
    {
        return $this->hasMany(Teaching::class);
    }

    public function userComment()
    {
        return $this->hasMany(UserComment::class, 'user_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    // Rest omitted for brevity
    public function authorPosts()
    {
        return $this->hasMany(Post::class,'user_id', 'id')->select(['title', 'img', 'short_description', 'teaching_id', 'post_type_id', 'duration']);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function audioPosts(){
        return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=', PostTypeController::typeAudio());
    }

    public function articlePosts()
    {
        return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=', PostTypeController::typeArticle());
    }
//    public function articlePosts(PostType $postType)
//    {
//        return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=',$postType->id);
//    }
//    public function audioPosts(){
//        return $this->hasMany(Post::class,'user_id', 'id')->where('post_type_id', '=', PostTypeController::typeAudio());
//    }
}
