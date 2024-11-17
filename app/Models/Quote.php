<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function likes()
    {
        return $this->hasMany(Like::class)->where('like', true);
    }

    public function dislikes()
    {
        return $this->hasMany(Like::class)->where('like', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
