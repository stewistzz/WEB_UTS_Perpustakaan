<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    // Implementasi DB Facade
    
    //function index untuk menampilkan halaman dari route
    public function index() {
        $data = DB::select('select * from books');
        return view('book', ['data' => $data]);
    }
}
