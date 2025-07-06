<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\courses;
use App\Models\User;
class courseUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'FIO',
        'age',
        'city'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course() {
        return $this->belongsTo(courses::class, 'course_id');
    }
}
