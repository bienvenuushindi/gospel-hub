<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    //
    use SoftDeletes;

//    protected $fillable = [
//        'user_id',
//        'title',
//        'venue',
//        'poster_image',
//        'note',
//        'starting_time',
//        'ending_time',
//        'starting_date',
//        'ending_date',
//        'price',
//    ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}
