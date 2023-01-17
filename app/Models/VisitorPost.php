<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorPost extends Model
{
    //
//    protected $fillable = [
//        'email', 'name', 'notify_via_email', 'comment_id'
//    ];
    protected $guarded = [];

    //
    public function visitorComment()
    {
        return $this->belongsTo(PostComment::class)->where('user_type', '=', 'visitor');;
    }
}
