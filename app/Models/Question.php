<?php

namespace App\Models;

use App\Enums\QuestionStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'status'];

    protected $casts = [
        'status' => QuestionStatusEnum::class,
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', QuestionStatusEnum::ANSWERED);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', QuestionStatusEnum::OPEN);
    }

    public function scopeIgnored(Builder $query): Builder
    {
        return $query->where('status', QuestionStatusEnum::IGNORED);
    }
}
