<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = ['name'];

    public function postTags()
    {
        return $this->hasMany(PostTag::class);
    }

    public function tagTeachings()
    {
        return $this->hasMany(TagTeaching::class);
    }

    public function teachings()
    {
        return $this->belongsToMany(Teaching::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags', 'tag_id', 'post_id')->withTimestamps();;
    }
}
