<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\field;
use Illuminate\Support\Facades\DB;

class DisturbanceController extends Controller
{
    function Dist($id) {
        $data = DB::table('tents')->get();
        return view('test',['id' => $id, "cas" => 0, 'data' => $data]);
    }
}
