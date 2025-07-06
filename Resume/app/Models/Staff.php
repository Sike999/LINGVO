<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Person;

class Staff extends Model
{
    use HasFactory;

    public function people(){
    return $this->hasMany(Person::class, 'Staff', 'id');}
}
