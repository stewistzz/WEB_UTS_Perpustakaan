<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index()
    {
        $data = DB::select('select * from borrowings');
        return view('borrow', ['data' => $data]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        Book::where('id', $request->book_id)->decrement('stock');

        return redirect()->back()->with('success', 'Buku berhasil dipinjam');
    }
}
