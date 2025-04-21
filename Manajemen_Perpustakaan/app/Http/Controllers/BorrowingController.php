<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    // Menampilkan halaman utama daftar peminjaman
    public function index()
    {
        // Menentukan breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Daftar Peminjaman',
            'list'  => ['Home', 'Peminjaman']
        ];

        // Menentukan halaman untuk judul halaman
        $page = (object) [
            'title' => 'Daftar peminjaman yang terdaftar dalam sistem'
        ];

        // Menandakan menu yang sedang aktif
        $activeMenu = 'borrow'; 

        // Mengambil semua data peminjaman
        $borrow = Borrowing::all();

        // Menampilkan view dengan data yang dibutuhkan
        return view('borrow.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'borrow' => $borrow,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan data peminjaman dalam format DataTables
    public function list(Request $request)
    {
        // Mengambil data peminjaman untuk ditampilkan
        $borrows = Borrowing::select(
            'id',
            'user_id',
            'book_id',
            'borrowed_at',
            'status'
        );

        // Menggunakan DataTables untuk menampilkan data
        return DataTables::of($borrows)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($borrow) {
                // Menambahkan tombol untuk aksi detail dan edit
                $btn = '<button onclick="modalAction(\'' . url('/borrow/' . $borrow->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/borrow/' . $borrow->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/borrow/' . $borrow->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan detail peminjaman berdasarkan ID
    public function show($id)
    {
        // Memuat relasi user dan book agar bisa ditampilkan di view
        $borrow = Borrowing::with(['user', 'book'])->find($id);

        // Menentukan breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Peminjaman',
            'list' => ['Home', 'Borrowing', 'Detail']
        ];

        // Menentukan halaman untuk judul halaman
        $page = (object) [
            'title' => 'Detail Peminjaman'
        ];

        // Menandakan menu yang sedang aktif
        $activeMenu = 'borrow';

        // Menampilkan view dengan data yang dibutuhkan
        return view('borrow.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'borrow' => $borrow,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan form edit peminjaman via modal
    public function edit_ajax(string $id)
    {
        // Mengambil data peminjaman, users, dan books untuk dropdown
        $borrow = Borrowing::find($id);
        $users = User::all();
        $books = Book::all();

        return view('borrow.edit_ajax', compact('borrow', 'users', 'books'));
    }

    // Proses untuk memperbarui data peminjaman
    public function update_ajax(Request $request, $id)
    {
        // Memeriksa apakah request menggunakan ajax atau json
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk data yang dimasukkan
            $rules = [
                'user_id'      => 'required|exists:users,id',
                'book_id'      => 'required|exists:books,id',
                'borrow_date'  => 'required|date',
                'due_date'     => 'required|date',
                'returned_at'  => 'nullable|date|after_or_equal:due_date',
                'status'       => 'required|string|in:dipinjam,dikembalikan',
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'msgField'  => $validator->errors()
                ]);
            }

            // Mengambil data peminjaman dan memperbarui dengan data baru
            $borrow = Borrowing::find($id);

            if ($borrow) {
                $borrow->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data peminjaman berhasil diperbarui'
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

    // Tampilkan konfirmasi hapus buku
    public function confirm_ajax(string $id)
    {
        $borrow = Borrowing::find($id);
        return view('borrow.confirm_ajax', ['borrow' => $borrow]);
    }

    // Menghapus buku dari database (AJAX)
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Temukan buku
            $borrow = Borrowing::find($id);

            // Jika buku ditemukan, hapus
            if ($borrow) {
                $borrow->delete();
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
