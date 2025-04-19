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
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Daftar Peminjaman',
            'list'  => ['Home', 'Peminjaman']
        ];

        $page = (object) [
            'title' => 'Daftar peminjaman yang terdaftar dalam sistem'
        ];

        $activeMenu = 'booking'; // set menu yang sedang aktif

        $booking = Borrowing::all();

        return view('booking.booking', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'borrow' => $booking,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list_booking(Request $request)
    {
        $bookings = Borrowing::select(
            'id',
            'user_id',
            'book_id',
            'borrowed_at',
            'due_date',
            'status'
        )
            ->where('user_id', auth()->id()) // Hanya data user yang login
            ->latest();

        return DataTables::of($bookings)
            ->addIndexColumn()
            ->addColumn('aksi', function ($booking) {
                $btn = '<button onclick="modalAction(\'' . url('/booking/' . $booking->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Method untuk menampilkan detail peminjaman
    public function show($id)
    {
        // Memuat relasi user dan book agar bisa ditampilkan di view
        $booking = Borrowing::with(['user', 'book'])->find($id);

        // dd($booking);

        $breadcrumb = (object) [
            'title' => 'Detail Peminjaman',
            'list' => ['Home', 'Booking', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Booking'
        ];

        $activeMenu = 'booking';

        return view('booking.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'booking' => $booking,
            'activeMenu' => $activeMenu
        ]);
    }

    // create booking untuk peran user
    public function create_ajax()
    {
        // Ambil data buku untuk dropdown
        $books = Book::where('stock', '>', 0)->get();
        return view('booking.create_ajax', compact('books'));
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
                'due_date'    => Carbon::now()->addWeek(), // default
                'returned_at' => $request->returned_at, // default
                'status'      => 'dipinjam', // default string
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data peminjaman berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
}
