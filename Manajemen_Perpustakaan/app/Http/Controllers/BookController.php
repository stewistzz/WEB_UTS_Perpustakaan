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
    // function
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Daftar Buku',
            'list'  => ['Home', 'Buku']
        ];

        $page = (object) [
            'title' => 'Daftar buku yang terdaftar dalam sistem'
        ];

        $activeMenu = 'book'; // set menu yang sedang aktif

        $book = Book::all();

        return view('book.book', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'book' => $book,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $books = Book::select('id', 'title', 'author', 'publisher', 'year', 'category_id', 'stock');

        return DataTables::of($books)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($book) {
                $btn = '<button onclick="modalAction(\'' . url('/book/' . $book->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/book/' . $book->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/book/' . $book->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Method untuk show detail buku
    public function show($id)
    {
        $book = Book::with('category')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Buku',
            'list' => ['Home', 'Book', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Buku'
        ];

        $activeMenu = 'book';

        return view('book.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'book' => $book,
            'activeMenu' => $activeMenu
        ]);
    }

    // Tampilkan form tambah buku via modal AJAX
    public function create_ajax()
    {
        $categories = Category::all(); // untuk pilihan dropdown kategori
        return view('book.create_ajax', compact('categories'));
    }

    // Simpan buku baru
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'title' => 'required|string|min:2',
                'author' => 'required|string|min:2',
                'publisher' => 'required|string|min:2',
                'year' => 'required|numeric|digits:4',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            Book::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Tampilkan form edit buku via modal
    public function edit_ajax(string $id)
    {
        $book = Book::find($id);
        $categories = Category::all();
        return view('book.edit_ajax', compact('book', 'categories'));
    }

    // Proses update buku
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'title' => 'required|string|min:2',
                'author' => 'required|string|min:2',
                'publisher' => 'required|string|min:2',
                'year' => 'required|numeric|digits:4',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'msgField'  => $validator->errors()
                ]);
            }

            $book = Book::find($id);

            if ($book) {
                $book->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Buku berhasil diperbarui'
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

    // Tampilkan konfirmasi hapus
    public function confirm_ajax(string $id)
    {
        $book = Book::find($id);
        return view('book.confirm_ajax', ['book' => $book]);
    }

    // Hapus buku
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $book = Book::find($id);

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
