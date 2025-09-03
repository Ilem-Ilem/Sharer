<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Note extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'content', 'file_path', 'visibility'];

    protected $casts = [
        'visibility' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function noteAi()
    {
        return $this->hasOne(NoteAi::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'note_tags');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function collaborations()
    {
        return $this->hasMany(Collaboration::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
              ->orWhere(function ($q) use ($user) {
                  $q->where('visibility', 'friends')
                    ->whereIn('user_id', $user->following()->pluck('followed_id'));
              })
              ->orWhere('user_id', $user->id);
        });
    }
}
