<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $guarded = false;
    
    protected $appends = ['questions_count'];
    
    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }
    
    public function questions(): HasMany
    {
        return $this->HasMany(TestQuestion::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'courses_id');
    }
    
    public function results(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }
}
