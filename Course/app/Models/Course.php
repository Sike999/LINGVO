<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'lid',
        'content',
        'rubric_id',
        'image',
        'date',
        'capacity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    function users()
    {
        return $this->belongsToMany(User::class);
    }

    function rubrics()
    {
        return $this->belongsTo(Rubric::class);
    }

    public function isFull(): bool
    {
        return $this->users()->count() >= $this->capacity;
    }
}
