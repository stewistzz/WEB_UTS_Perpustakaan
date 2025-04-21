<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Menampilkan halaman utama daftar kategori
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Category',  // Judul halaman
            'list'  => ['Home', 'Category']  // Rangkaian breadcrumb untuk navigasi
        ];

        $page = (object) [
            'title' => 'Daftar kategori buku yang terdaftar dalam sistem'  // Deskripsi halaman
        ];

        $activeMenu = 'category'; // Menandai menu 'Category' sebagai aktif

        // Mengambil semua data kategori dari database
        $category = Category::all();

        // Mengembalikan tampilan 'category.category' dengan data yang telah disiapkan
        return view('category.category', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'category' => $category,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan daftar kategori dalam bentuk DataTable
    public function list()
    {
        $categories = Category::select('id', 'name');

        return DataTables::of($categories)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($category) {
                // Menambahkan tombol untuk aksi seperti detail, edit, hapus
                $btn = '<button onclick="modalAction(\'' . url('/category/' . $category->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/category/' . $category->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/category/' . $category->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Menandakan bahwa kolom aksi mengandung HTML
            ->make(true); // Menghasilkan data dalam format yang diterima oleh DataTables
    }

    // Menampilkan detail kategori berdasarkan ID
    public function show($id)
    {
        $category = Category::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail category',  // Judul halaman detail
            'list' => ['Home', 'category', 'Detail']  // Navigasi breadcrumb
        ];

        $page = (object) [
            'title' => 'Detail category'  // Deskripsi halaman detail
        ];

        $activeMenu = 'category'; // Menandai menu 'Category' sebagai aktif

        // Mengembalikan tampilan 'category.show' dengan data kategori
        return view('category.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'category' => $category,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan form tambah kategori menggunakan AJAX
    public function create_ajax()
    {
        return view('category.create_ajax');  // Mengembalikan tampilan form tambah kategori
    }

    // Menyimpan kategori baru menggunakan AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk nama kategori
            $rules = [
                'name' => 'required|string|min:2|unique:categories,name',
            ];

            // Melakukan validasi input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // Jika validasi gagal, kembalikan pesan error
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Menyimpan kategori baru ke dalam database
            Category::create($request->all());

            // Mengembalikan respon sukses
            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil disimpan'
            ]);
        }

        return redirect('/'); // Redirect jika bukan request AJAX
    }

    // Menampilkan form edit kategori menggunakan AJAX
    public function edit_ajax(string $id)
    {
        $category = Category::find($id);
        return view('category.edit_ajax', ['category' => $category]);  // Mengembalikan tampilan form edit kategori
    }

    // Memperbarui kategori menggunakan AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk nama kategori
            $rules = [
                'name' => ['required', 'string', 'min:2', 'unique:categories,name,' . $id . ',id']
            ];

            // Melakukan validasi input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // Jika validasi gagal, kembalikan pesan error
                return response()->json([
                    'status'    => false,
                    'msgField'  => $validator->errors()
                ]);
            }

            $category = Category::find($id);

            if ($category) {
                // Memperbarui kategori jika ditemukan
                $category->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Kategori berhasil diperbarui'
                ]);
            } else {
                // Mengembalikan respon jika data tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/'); // Redirect jika bukan request AJAX
    }

    // Menampilkan konfirmasi hapus kategori menggunakan AJAX
    public function confirm_ajax(string $id)
    {
        $category = Category::find($id);
        return view('category.confirm_ajax', ['category' => $category]);  // Mengembalikan tampilan konfirmasi hapus kategori
    }

    // Menghapus kategori menggunakan AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $category = Category::find($id);

            if ($category) {
                // Menghapus kategori jika ditemukan
                $category->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Kategori berhasil dihapus'
                ]);
            } else {
                // Mengembalikan respon jika data tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/'); // Redirect jika bukan request AJAX
    }
}
