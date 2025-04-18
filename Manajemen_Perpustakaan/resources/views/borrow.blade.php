@extends('layouts.template')

@section('content')
<div class="container">
    <h3>Daftar Peminjaman Buku</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('borrow.store') }}">
        @csrf
        <div class="form-group">
            <label for="book_id">Pilih Buku</label>
            <select name="book_id" id="book_id" class="form-control">
                @foreach(App\Models\Book::all() as $book)
                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Pinjam Buku</button>
    </form>

    <hr>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Book</th>
                <th>Borrowed At</th>
                <th>Due Date</th>
                <th>Returned At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $borrow)
            <tr>
                <td>{{ $borrow->id }}</td>
                <td>{{ $borrow->user_id }}</td>
                <td>{{ $borrow->book_id }}</td>
                <td>{{ $borrow->borrowed_at }}</td>
                <td>{{ $borrow->due_date }}</td>
                <td>{{ $borrow->returned_at ?? '-' }}</td>
                <td>{{ $borrow->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
