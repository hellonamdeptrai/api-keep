<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'index',
        'title',
        'content',
        'is_check_box_or_content',
        'deadline',
        'color',
        'background',
        'archive',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'note_has_user', 'note_id', 'user_id');
    }
}
