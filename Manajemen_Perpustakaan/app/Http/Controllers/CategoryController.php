<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // Implementasi DB Facade

    //function index untuk menampilkan halaman dari route
    public function index() {
        $data = DB::select('select * from categories');
        return view('category', ['data' => $data]);
    }
}
