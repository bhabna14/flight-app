<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;
    protected $table = 'social_medias';
 
    protected $fillable = [
        'user_id',
        'facebookurl',
        'instagramurl',
        'youtubeurl',
        'twitterurl',
        'linkedinurl', // Add linkedinurl here
    ];

}
