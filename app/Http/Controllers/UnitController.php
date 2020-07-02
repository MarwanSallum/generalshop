<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(){
        $units = Unit::paginate(16);
        return view('admin.units.units') -> with(['units' => $units]);
    }

    public function showAdd(){
        return view('admin.units.add_edit');
    }
}
