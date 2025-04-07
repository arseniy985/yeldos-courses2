<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courses extends Model
{
    use HasFactory;

    /**
     * Тесты курса
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'courses_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(CourseEpisode::class, 'course_id');
    }
}
