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
    // Fungsi untuk menampilkan halaman utama (dashboard)
    public function index() {
        // Menyiapkan data breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Selamat Datang', // Judul halaman
            'list' => ['Home', 'Welcome'] // Menu navigasi
        ];

        // Menentukan menu yang aktif di sidebar
        $activeMenu = 'dashboard';

        // Cek role atau peran pengguna yang sedang login
        $role = Auth::user()->role;

        // Inisialisasi variabel default untuk menghitung data
        $userCount = 0;
        $categoryCount = 0;
        $bookCount = 0;
        $borrowCount = 0;

        // Jika role pengguna adalah admin, ambil data statistik
        if ($role === 'admin') {
            $userCount = User::count(); // Menghitung jumlah pengguna
            $categoryCount = Category::count(); // Menghitung jumlah kategori buku
            $bookCount = Book::count(); // Menghitung jumlah buku
            $borrowCount = Borrowing::count(); // Menghitung jumlah peminjaman buku
        }

        // Mengirim data ke tampilan (view) untuk ditampilkan pada halaman
        return view('welcome', [
            'breadcrumb' => $breadcrumb, // Data breadcrumb
            'activeMenu' => $activeMenu, // Menu yang aktif
            'userCount' => $userCount, // Jumlah pengguna
            'categoryCount' => $categoryCount, // Jumlah kategori buku
            'bookCount' => $bookCount, // Jumlah buku
            'borrowCount' => $borrowCount, // Jumlah peminjaman buku
        ]);
    }
}
