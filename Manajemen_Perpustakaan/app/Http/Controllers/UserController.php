<?php

namespace App\Http\Controllers;

use App\Models\User; // Model User digunakan untuk interaksi dengan tabel pengguna
use Illuminate\Http\Request; // Digunakan untuk menangani permintaan HTTP
use Illuminate\Support\Facades\Hash; // Digunakan untuk hash password
use Illuminate\Support\Facades\Validator; // Digunakan untuk validasi input
use Yajra\DataTables\Facades\DataTables; // Digunakan untuk menampilkan data dalam bentuk tabel interaktif

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        // Menyiapkan breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        // Menyiapkan informasi halaman
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        // Menandakan menu yang sedang aktif
        $activeMenu = 'user'; // set menu yang sedang aktif

        // Mengembalikan tampilan dengan data breadcrumb, page, dan activeMenu
        return view('user.user', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Mengambil data pengguna dan menampilkannya dalam bentuk tabel dengan DataTables
    public function list(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'role'); // Ambil data pengguna

        // Menampilkan data dalam format tabel interaktif
        return DataTables::of($users)
            ->addIndexColumn() // Menambahkan kolom index untuk nomor urut
            ->addColumn('aksi', function ($user) { // Menambahkan kolom aksi untuk setiap pengguna
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; // Mengembalikan tombol aksi
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi berisi HTML
            ->make(true); // Menghasilkan response dalam format yang sesuai untuk DataTables
    }

    // Menampilkan detail informasi pengguna
    public function show($id)
    {
        $user = User::find($id); // Mencari pengguna berdasarkan ID

        // Menyiapkan breadcrumb dan informasi halaman
        $breadcrumb = (object) [
            'title' => 'Detail Pengguna',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Pengguna'
        ];

        $activeMenu = 'user';

        // Mengembalikan tampilan dengan data pengguna yang ditemukan
        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan form untuk menambah pengguna baru melalui modal AJAX
    public function create_ajax()
    {
        return view('user.create_ajax');
    }

    // Menyimpan data pengguna baru melalui AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => 'required|string|min:2', // Validasi nama
                'email' => 'required|email|unique:users,email', // Validasi email unik
                'role' => 'required|string', // Validasi peran
                'password' => 'required|string|min:6', // Validasi password
            ];

            $validator = Validator::make($request->all(), $rules); // Validasi input pengguna

            // Jika validasi gagal, kembalikan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $data = $request->all();
            $data['password'] = bcrypt($data['password']); // Enkripsi password
            User::create($data); // Menyimpan pengguna baru

            return response()->json([
                'status' => true,
                'message' => 'Pengguna berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan form untuk mengedit pengguna melalui modal AJAX
    public function edit_ajax(string $id)
    {
        $user = User::find($id); // Mencari pengguna berdasarkan ID
        return view('user.edit_ajax', ['user' => $user]); // Mengembalikan tampilan edit
    }

    // Memperbarui data pengguna
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => 'required|string|min:2', // Validasi nama
                'email' => 'required|email|unique:users,email,' . $id, // Validasi email, kecuali yang sudah ada
                'role' => 'required|string', // Validasi peran
            ];

            // Validasi password hanya jika diisi
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:6'; // Validasi password
            }

            $validator = Validator::make($request->all(), $rules); // Validasi input pengguna

            // Jika validasi gagal, kembalikan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msgField' => $validator->errors()
                ]);
            }

            $user = User::find($id); // Mencari pengguna berdasarkan ID

            // Jika pengguna ditemukan, perbarui data pengguna
            if ($user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role;
                if ($request->filled('password')) {
                    $user->password = bcrypt($request->password); // Enkripsi password jika ada perubahan
                }
                $user->save(); // Simpan perubahan data pengguna

                return response()->json([
                    'status' => true,
                    'message' => 'Pengguna berhasil diperbarui'
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

    // Menampilkan konfirmasi sebelum menghapus pengguna
    public function confirm_ajax(string $id)
    {
        $user = User::find($id); // Mencari pengguna berdasarkan ID
        return view('user.confirm_ajax', ['user' => $user]); // Mengembalikan tampilan konfirmasi
    }

    // Menghapus pengguna melalui AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = User::find($id); // Mencari pengguna berdasarkan ID

            // Jika pengguna ditemukan, hapus data pengguna
            if ($user) {
                $user->delete(); // Hapus pengguna dari database
                return response()->json([
                    'status' => true,
                    'message' => 'Pengguna berhasil dihapus'
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
