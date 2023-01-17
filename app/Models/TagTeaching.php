<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagTeaching extends Model
{
    //
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }
}
