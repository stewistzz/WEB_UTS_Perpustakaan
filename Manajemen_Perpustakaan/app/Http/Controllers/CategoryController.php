<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // function
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Daftar Category',
            'list'  => ['Home', 'Category']
        ];

        $page = (object) [
            'title' => 'Daftar category buku yang terdaftar dalam sistem'
        ];

        $activeMenu = 'category'; // set menu yang sedang aktif

        $category = Category::all();

        return view('category.category', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'category' => $category,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        $categories = Category::select('id', 'name');

        return DataTables::of($categories)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($category) {
                $btn = '<button onclick="modalAction(\'' . url('/category/' . $category->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/category/' . $category->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/category/' . $category->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // method untuk show detail
    public function show($id)
    {
        $category = Category::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail category',
            'list' => ['Home', 'category', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail category'
        ];

        $activeMenu = 'category'; // Set menu yang sedang aktif

        return view('category.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'category' => $category,
            'activeMenu' => $activeMenu
        ]);
    }

    // menggunakan ajax
    // Tampilkan form tambah kategori via modal AJAX
    public function create_ajax()
    {
        return view('category.create_ajax');
    }

    // Simpan kategori baru
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => 'required|string|min:2|unique:categories,name',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            Category::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Tampilkan form edit kategori via modal
    public function edit_ajax(string $id)
    {
        $category = Category::find($id);
        return view('category.edit_ajax', ['category' => $category]);
    }

    // Proses update kategori
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => ['required', 'string', 'min:2', 'unique:categories,name,' . $id . ',id']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'msgField'  => $validator->errors()
                ]);
            }

            $category = Category::find($id);

            if ($category) {
                $category->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Kategori berhasil diperbarui'
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
        $category = Category::find($id);
        return view('category.confirm_ajax', ['category' => $category]);
    }

    // Hapus kategori
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $category = Category::find($id);

            if ($category) {
                $category->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Kategori berhasil dihapus'
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
