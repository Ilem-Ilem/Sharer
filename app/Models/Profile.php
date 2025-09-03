<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'username', 'avatar', 'cover_photo', 'bio', 'location',
        'website', 'birthday', 'gender', 'social_links', 'visibility', 'theme', 'language', 'field_of_study', 'education', 'occupation'
    ];

    protected $casts = [
        'social_links' => 'array',
        'birthday' => 'date',
        'visibility' => 'string',
        'theme' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
