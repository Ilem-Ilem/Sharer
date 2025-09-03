<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteAi extends Model
{
    protected $table = 'note_ai';
    protected $fillable = ['note_id', 'summary', 'keywords', 'embedding', 'topics', 'qa_cache', 'generated_by'];

protected $casts = [
    'keywords' => 'array',
    'embedding' => 'array',
    'topics' => 'array',
    'qa_cache' => 'array',
];

public function note()
{
    return $this->belongsTo(Note::class);
}
}
