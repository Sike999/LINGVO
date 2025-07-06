<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\categories;
use Illuminate\Database\Eloquent\SoftDeletes;
class news extends Model
{
    use HasFactory;
    protected $fillable = [
        'link',
        'head',
        'text',
        'cat_id',
        'img'
    ];

    public function categories() {
        return $this->belongsTo(categories::class, 'cat_id', 'id');
    }
    public static function getAll() {
        return self::with('categories')->select('id','link','head','text','cat_id','img')->orderBy('created_at','desc')->get();        
    }
    public static function getSorted($sorted) {
        return self::with('categories')->select('id','link','head','text','cat_id','img')->where('cat_id', $sorted)->orderBy('created_at','desc')->get();
    }
    public static function forNav() {
        return self::with('categories')->select('cat_id')->distinct()->get();
    }
    public static function add($data) {
        return self::create($data);
    }
}

