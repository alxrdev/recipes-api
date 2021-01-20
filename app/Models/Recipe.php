<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image',
        'preparation_time',
        'ingredients',
        'steps',
        'difficulty'
    ];

    public function setDifficulty($value)
    {
        $value = (int) $value;
        $value = (empty($value) || $value < 1) ? 1 : $value;

        $this->difficulty = ($value > 5) ? 5 : $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
