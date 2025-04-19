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
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Daftar Peminjaman',
            'list'  => ['Home', 'Peminjaman']
        ];

        $page = (object) [
            'title' => 'Daftar peminjaman yang terdaftar dalam sistem'
        ];

        $activeMenu = 'borrow'; // set menu yang sedang aktif

        $borrow = Borrowing::all();

        return view('borrow.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'borrow' => $borrow,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $borrows = Borrowing::select(
            'id',
            'user_id',
            'book_id',
            'borrowed_at',
            // 'due_date', 
            // 'returned_at', 
            'status'
        );

        return DataTables::of($borrows)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($borrow) {
                $btn = '<button onclick="modalAction(\'' . url('/borrow/' . $borrow->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/borrow/' . $borrow->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    

    // Method untuk menampilkan detail peminjaman
    public function show($id)
    {
        // Memuat relasi user dan book agar bisa ditampilkan di view
        $borrow = Borrowing::with(['user', 'book'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Peminjaman',
            'list' => ['Home', 'Borrowing', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Peminjaman'
        ];

        $activeMenu = 'borrow';

        return view('borrow.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'borrow' => $borrow,
            'activeMenu' => $activeMenu
        ]);
    }

    // Tampilkan form edit peminjaman via modal
    public function edit_ajax(string $id)
    {
        $borrow = Borrowing::find($id);
        $users = User::all(); // Untuk dropdown user
        $books = Book::all(); // Untuk dropdown buku
        return view('borrow.edit_ajax', compact('borrow', 'users', 'books'));
    }

    // Proses update peminjaman
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id'      => 'required|exists:users,id',
                'book_id'      => 'required|exists:books,id',
                'borrow_date'  => 'required|date',
                'due_date'     => 'required|date',
                'returned_at'  => 'nullable|date|after_or_equal:due_date',
                'status'       => 'required|string|in:dipinjam,dikembalikan',
            ];

            // dd($request->all());

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'msgField'  => $validator->errors()
                ]);
            }

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

    // create booking untuk peran user
    public function create_ajax()
    {
        // Ambil data buku untuk dropdown
        $books = Book::where('stock', '>', 0)->get();
        return view('borrowing.create_ajax', compact('books'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi hanya kolom yang diperlukan
            $rules = [
                'book_id'     => 'required|exists:books,id',
                'borrowed_at' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validasi gagal',
                    'msgField'  => $validator->errors(),
                ]);
            }

            // Simpan hanya 3 kolom utama, lainnya set default
            Borrowing::create([
                'user_id'     => auth()->id(),
                'book_id'     => $request->book_id,
                'borrowed_at' => $request->borrowed_at,
                'due_date'    => null, // default
                'returned_at' => null, // default
                'status'      => 'none', // default string
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data peminjaman berhasil disimpan'
            ]);
        }

        return redirect('/');
    }


    public function list_booking(Request $request)
    {
        $bookings = Borrowing::select(
            'id',
            'user_id',
            'book_id',
            'borrowed_at',
            // 'due_date', 
            // 'returned_at', 
            'status'
        );

        return DataTables::of($bookings)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($booking) {
                $btn = '<button onclick="modalAction(\'' . url('/booking/' . $booking->id . '/create_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
