<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku Perpustakaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Daftar Buku Perpustakaan</h2>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->publisher }}</td>
                    <td>{{ $book->year }}</td>
                    <td>{{ $book->category_id }}</td>
                    <td>{{ $book->stock }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada buku</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
