<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kategori Buku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Daftar Kategori Buku</h2>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada kategori</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
