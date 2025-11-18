<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_picture', 
        'display_name', 
        'gender',
        'phone', 
        'bio', 
    ];

    // relationship with user table; this profile belongs to this user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     // Accessor for profile picture
    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? Storage::url($this->profile_picture)
            : asset('images/blog-details.png');
    }
}
