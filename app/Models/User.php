<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hasLiked($quoteId)
    {
        return $this->likes()->where('quote_id', $quoteId)->where('like', true)->exists();
    }

    public function hasDisliked($quoteId)
    {
        return $this->likes()->where('quote_id', $quoteId)->where('like', false)->exists();
    }

    public function toggleLikeDislike($quoteId, $like)
    {
        $existingLike = $this->likes()->where('quote_id', $quoteId)->first();
        $quote = Quote::find($quoteId);
        $userId = Auth::id();

        if ($quote->user_id == $userId)
        {
            return response()->json(['message' => 'You cannot like or dislike your own quote!']);
        }


        if ($existingLike)
        {
            if ($existingLike->like == $like)
            {
                $existingLike->delete();

                return [
                    'hasLiked' => false,
                    'hasDisliked' => false
                ];
            }
            else
            {
                $existingLike->update(['like' => $like]);
            }
        }
        else
        {
            $this->likes()->create([
                'quote_id' => $quoteId,
                'like' => $like,
            ]);
        }
        return [
            'hasLiked' => $this->hasLiked($quoteId),
            'hasDisliked' => $this->hasDisliked($quoteId)
        ];
    }
}
