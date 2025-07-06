<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Staff;

class Person extends Model
{
    use HasFactory;
    protected $fillable = [
        'FIO',
        'Staff',
        'Phone',
        'Stage',
        'Image'
    ];

    public function staff() {
    return $this->belongsTo(Staff::class, 'Staff', 'id');}
    
    public static function first() {
    return self::whereBetween('Stage', [5,15])->select('FIO')->get();}

    public static function second() {
    return self::where('Staff',1)->select('FIO','Stage')->get();}

    public static function third() {
    return self::count();}

    public static function fourth() {
    return self::join('Staff', 'people.Staff', '=', 'Staff.id')->select('Staff.staff')->distinct()->get();}

    public static function getMembers() {
        return self::with('staff')->select('FIO', 'Phone', 'Stage', 'id', 'Image', 'Staff')->get();
    }
    public function change($FIO,$Staff,$Phone,$Stage,$Image) {
        return $this->update(['FIO' => $FIO, 'Staff' => $Staff, 'Phone' => $Phone, 'Stage' => $Stage, 'Image' => $Image]);
    }
    public function add($data) {
        return $this->create($data);
    }
}
