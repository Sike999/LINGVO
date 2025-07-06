<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\news;
class categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ["category"];
    public function news() {
    return $this->hasMany(news::class, 'cat_id', 'id');}

    public static function forCreate() {
        return self::select('id','category')->get();
    }

    public static function newCat($str) {
        return self::create(['category' => $str]);
    }
}
