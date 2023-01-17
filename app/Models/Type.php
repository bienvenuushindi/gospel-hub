<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    public function scopeAdmin($query)
    {
        return $query->where('name','admin');
    }

    public function scopeAuthor($query)
    {
        return $query->where('name','author');
    }

    public function scopeRegisteredUser($query)
    {
        return $query->where('name','normal');
    }

    public function users()
    {
        return $this->hasMany(Users::class);
    }
}
