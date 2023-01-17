<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teaching extends Model
{

    use SoftDeletes;

//    protected $fillable = [
//        'theme', 'image', 'user_id'
//    ];
    protected $guarded = [];

    //
    public function tagTeaching()
    {
        return $this->belongsTo(TagTeaching::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function audioPosts(Teaching $teaching){
        return $this->hasMany(Post::class,'teaching_id', 'id')->where('teaching_id', '=',$teaching->id);
    }

    public function articlePosts(Teaching $teaching)
    {
        return $this->hasMany(Post::class,'teaching_id', 'id')->where('teaching_id', '=', $teaching->id);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
