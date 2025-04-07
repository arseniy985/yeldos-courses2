<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEpisode extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }
}
