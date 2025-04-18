<?php

namespace App\Http\Controllers;

// use App\Models\LevelModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif


        return view('user.user', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }


    public function list(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'role');

        // Filter data user berdasarkan level_id if ($request->level_id){
        // if ($request->level_id) {
        //     $users->where('level_id', $request->level_id);
        // }

        return DataTables::of($users)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan detail user
    public function show($id)
    {
        $user = User::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Pengguna',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Pengguna'
        ];

        $activeMenu = 'user';

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    // Tampilkan form tambah user via modal AJAX
    public function create_ajax()
    {
        return view('user.create_ajax');
    }

    // Simpan user baru
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => 'required|string|min:2',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|string',
                'password' => 'required|string|min:6',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $data = $request->all();
            $data['password'] = bcrypt($data['password']); // enkripsi password
            User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Pengguna berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Tampilkan form edit user via modal AJAX
    public function edit_ajax(string $id)
    {
        $user = User::find($id);
        return view('user.edit_ajax', ['user' => $user]);
    }

    // Update user
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => 'required|string|min:2',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|string',
            ];

            // Optional password field
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:6';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msgField' => $validator->errors()
                ]);
            }

            $user = User::find($id);

            if ($user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role;
                if ($request->filled('password')) {
                    $user->password = bcrypt($request->password);
                }
                $user->save();

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

    // Tampilkan konfirmasi hapus user
    public function confirm_ajax(string $id)
    {
        $user = User::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }

    // Hapus user
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = User::find($id);

            if ($user) {
                $user->delete();
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