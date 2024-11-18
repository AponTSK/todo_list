<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
