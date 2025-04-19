<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Borrowing;

class WelcomeController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Cek role user
        $role = Auth::user()->role;

        // Inisialisasi variabel default
        $userCount = 0;
        $categoryCount = 0;
        $bookCount = 0;
        $borrowCount = 0;

        // Jika admin, ambil datanya
        if ($role === 'admin') {
            $userCount = User::count();
            $categoryCount = Category::count();
            $bookCount = Book::count();
            $borrowCount = Borrowing::count();
        }

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'userCount' => $userCount,
            'categoryCount' => $categoryCount,
            'bookCount' => $bookCount,
            'borrowCount' => $borrowCount,
        ]);
    }
}
