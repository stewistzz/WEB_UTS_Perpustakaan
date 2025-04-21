<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // Menampilkan halaman daftar buku
    public function index()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Daftar Buku',
            'list'  => ['Home', 'Buku']
        ];

        // Judul halaman
        $page = (object) [
            'title' => 'Daftar buku yang terdaftar dalam sistem'
        ];

        // Menu aktif di navbar
        $activeMenu = 'book';

        // Ambil semua data buku dari database
        $book = Book::all();

        // Kirim data ke view
        return view('book.book', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'book' => $book,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyediakan data buku dalam format DataTables (digunakan untuk AJAX table)
    public function list(Request $request)
    {
        // Pilih kolom yang akan ditampilkan
        $books = Book::select(
            'id', 
            'title', 
            'author', 
            'publisher', 
            'year', 
            'category_id', 
            'stock');

        // Buat struktur DataTables dengan kolom aksi (detail, edit, hapus)
        return DataTables::of($books)
            ->addIndexColumn() // Tambahkan nomor urut otomatis
            ->addColumn('aksi', function ($book) {
                // Tombol aksi yang ditampilkan di tabel
                $btn = '<button onclick="modalAction(\'' . url('/book/' . $book->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/book/' . $book->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/book/' . $book->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Izinkan HTML di kolom aksi
            ->make(true);
    }

    // Menampilkan detail satu buku
    public function show($id)
    {
        // Ambil data buku dengan relasi kategori
        $book = Book::with('category')->find($id);

        // Breadcrumb untuk halaman detail
        $breadcrumb = (object) [
            'title' => 'Detail Buku',
            'list' => ['Home', 'Book', 'Detail']
        ];

        // Judul halaman
        $page = (object) [
            'title' => 'Detail Buku'
        ];

        // Menu aktif
        $activeMenu = 'book';

        // Kirim data ke view
        return view('book.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'book' => $book,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan form tambah buku via modal (AJAX)
    public function create_ajax()
    {
        // Ambil semua kategori untuk dropdown
        $categories = Category::all();
        return view('book.create_ajax', compact('categories'));
    }

    // Simpan data buku baru (AJAX)
    public function store_ajax(Request $request)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {

            // Aturan validasi input
            $rules = [
                'title' => 'required|string|min:2',
                'author' => 'required|string|min:2',
                'publisher' => 'required|string|min:2',
                'year' => 'required|numeric|digits:4',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|numeric|min:0',
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            // Jika gagal validasi, kirim error dalam JSON
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Simpan buku baru ke database
            Book::create($request->all());

            // Kirim response berhasil
            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil disimpan'
            ]);
        }

        // Redirect jika bukan AJAX
        return redirect('/');
    }

    // Menampilkan form edit buku via modal (AJAX)
    public function edit_ajax(string $id)
    {
        // Ambil data buku berdasarkan ID
        $book = Book::find($id);

        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        // Tampilkan view dengan data
        return view('book.edit_ajax', compact('book', 'categories'));
    }

    // Memproses update data buku (AJAX)
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'title' => 'required|string|min:2',
                'author' => 'required|string|min:2',
                'publisher' => 'required|string|min:2',
                'year' => 'required|numeric|digits:4',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|numeric|min:0',
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'msgField'  => $validator->errors()
                ]);
            }

            // Temukan buku berdasarkan ID
            $book = Book::find($id);

            // Jika buku ditemukan, lakukan update
            if ($book) {
                $book->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Buku berhasil diperbarui'
                ]);
            } else {
                // Jika buku tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    // Tampilkan konfirmasi hapus buku
    public function confirm_ajax(string $id)
    {
        $book = Book::find($id);
        return view('book.confirm_ajax', ['book' => $book]);
    }

    // Menghapus buku dari database (AJAX)
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Temukan buku
            $book = Book::find($id);

            // Jika buku ditemukan, hapus
            if ($book) {
                $book->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Buku berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
}
