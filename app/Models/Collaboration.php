<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $fillable = ['note_id', 'owner_id', 'collaborator_id', 'role', 'status', 'expires_at', 'current_page', 'last_active_at'];

protected $casts = [
    'role' => 'string',
    'status' => 'string',
    'expires_at' => 'datetime',
];

public function note()
{
    return $this->belongsTo(Note::class);
}

public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}

public function collaborator()
{
    return $this->belongsTo(User::class, 'collaborator_id');
}

public function scopeActive($query)
{
    return $query->where('status', 'active')
                 ->where(function ($q) {
                     $q->whereNull('expires_at')
                       ->orWhere('expires_at', '>', now());
                 });
}
}
