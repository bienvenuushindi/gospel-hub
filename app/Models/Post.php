<?php

namespace App\Models;


use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTypeController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    //
    use SoftDeletes;

//    protected $fillable = [
//        'title', 'img', 'published', 'description', 'short_description', 'teaching_id', 'user_id', 'post_type_id', 'duration'
//    ];
    protected $guarded = [];

    public function getSlugAttribute(): string
    {
        return Str::slug($this->title);
    }
    public function getUrlAttribute(): string
    {
        return action('HomeController@viewAudio', [$this->id, $this->slug]);
    }
    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postType()
    {
        return $this->belongsTo(PostType::class);
    }

//    public function audioType(){
//        return $this->belongsTo(Post::class)->where('post_type_id',PostTypeController::typeAudio());
//    }
    public function postComments()
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id')->where('user_type', '=', 'user');
    }

    public function allPostComments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function postVisitorsComments()
    {
        return $this->hasMany(PostComment::class)->where('user_type', '=', 'visitor');
    }

    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function postViews()
    {
        return $this->hasMany(PostView::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id')->withTimestamps();
    }


}
