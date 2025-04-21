<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Method untuk menampilkan halaman utama daftar peminjaman
    public function index()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Daftar Peminjaman',
            'list'  => ['Home', 'Peminjaman']
        ];

        // Menyusun data halaman
        $page = (object) [
            'title' => 'Daftar peminjaman yang terdaftar dalam sistem'
        ];

        // Set menu yang sedang aktif
        $activeMenu = 'booking';

        // Mengambil semua data peminjaman
        $booking = Borrowing::all();

        // Menampilkan view dengan data yang diperlukan
        return view('booking.booking', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'borrow' => $booking,
            'activeMenu' => $activeMenu
        ]);
    }

    // Method untuk menampilkan daftar peminjaman user
    public function list_booking(Request $request)
    {
        // Mengambil data peminjaman dengan filter user yang sedang login
        $bookings = Borrowing::select(
            'id',
            'user_id',
            'book_id',
            'borrowed_at',
            'due_date',
            'status'
        )
            ->where('user_id', auth()->id()) // Hanya menampilkan peminjaman milik user yang login
            ->latest(); // Mengurutkan berdasarkan yang terbaru

        // Menggunakan DataTables untuk menampilkan data dengan lebih interaktif
        return DataTables::of($bookings)
            ->addIndexColumn() // Menambahkan kolom indeks
            ->addColumn('aksi', function ($booking) {
                // Menambahkan tombol untuk melihat detail peminjaman
                $btn = '<button onclick="modalAction(\'' . url('/booking/' . $booking->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Menandakan kolom 'aksi' sebagai raw HTML
            ->make(true); // Menghasilkan response JSON untuk DataTables
    }

    // Method untuk menampilkan detail peminjaman
    public function show($id)
    {
        // Mengambil data peminjaman beserta relasi user dan book
        $booking = Borrowing::with(['user', 'book'])->find($id);

        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Peminjaman',
            'list' => ['Home', 'Booking', 'Detail']
        ];

        // Menyusun data halaman
        $page = (object) [
            'title' => 'Detail Booking'
        ];

        // Set menu yang sedang aktif
        $activeMenu = 'booking';

        // Menampilkan view dengan data yang diperlukan
        return view('booking.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'booking' => $booking,
            'activeMenu' => $activeMenu
        ]);
    }

    // Method untuk menampilkan form booking baru (untuk user)
    public function create_ajax()
    {
        // Mengambil data buku yang masih tersedia
        $books = Book::where('stock', '>', 0)->get();
        return view('booking.create_ajax', compact('books')); // Menampilkan view untuk form booking
    }

    // Method untuk menyimpan peminjaman baru menggunakan AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input dari user
            $rules = [
                'book_id'     => 'required|exists:books,id', // Pastikan book_id ada dalam tabel books
                'borrowed_at' => 'required|date', // Pastikan borrowed_at adalah tanggal yang valid
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // Jika validasi gagal, kirimkan response error
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validasi gagal',
                    'msgField'  => $validator->errors(),
                ]);
            }

            // Menyimpan data peminjaman baru, dengan nilai default untuk kolom lainnya
            Borrowing::create([
                'user_id'     => auth()->id(), // ID user yang sedang login
                'book_id'     => $request->book_id,
                'borrowed_at' => $request->borrowed_at,
                'due_date'    => Carbon::now()->addWeek(), // Due date default 1 minggu dari sekarang
                'returned_at' => $request->returned_at, // default jika tidak ada pengembalian
                'status'      => 'dipinjam', // Status peminjaman
            ]);

            // Mengirimkan response sukses
            return response()->json([
                'status'  => true,
                'message' => 'Data peminjaman berhasil disimpan'
            ]);
        }

        return redirect('/'); // Jika bukan request AJAX, redirect ke halaman utama
    }
}
