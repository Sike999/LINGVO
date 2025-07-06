<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsToMany(courseUsers::class,'course_users', 'user_id', 'course_id');
    }

    public $timestamps = false;

    protected $casts = [
    'date' => 'datetime'];

    protected $fillable = [
        'title',
        'lid',
        'content',
        'rubric_id',
        'image',
        'date',
        'capacity',
    ];

    public function add($data){
       return self::create($data);
    }
    public static function getFiltered($id) {
        return self::where('rubric_id', '=', $id)->get();
    }
}
