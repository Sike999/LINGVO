<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubrics extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public $timestamps = false;

    public static function getRubrics() {
        return self::select('id','name')->get();
    }
    public function add($data) {
        self::create($data);
    }
}
