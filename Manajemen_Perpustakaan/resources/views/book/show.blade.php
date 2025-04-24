{{-- Menggunakan template dasar dari file layouts/template.blade.php --}}
@extends('layouts.template')

{{-- Menandai bahwa konten utama akan diisi di dalam section 'content' --}}
@section('content')

{{-- Header dari modal, menampilkan judul --}}
<div class="modal-header">
    <h5 class="modal-title">Detail Buku</h5>
</div>

{{-- Body dari modal --}}
<div class="modal-body">

    {{-- Mengecek apakah variabel $book kosong --}}
    @empty($book)
        {{-- Menampilkan alert jika data buku tidak ditemukan --}}
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data buku tidak ditemukan.
        </div>
    @else
        {{-- Jika $book tidak kosong, maka tampilkan detail buku dalam bentuk tabel --}}
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                {{-- Menampilkan ID buku --}}
                <td>{{ $book->id }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                {{-- Menampilkan judul buku --}}
                <td>{{ $book->title }}</td>
            </tr>
            <tr>
                <th>Penulis</th>
                {{-- Menampilkan nama penulis buku --}}
                <td>{{ $book->author }}</td>
            </tr>
            <tr>
                <th>Penerbit</th>
                {{-- Menampilkan nama penerbit buku --}}
                <td>{{ $book->publisher }}</td>
            </tr>
            <tr>
                <th>Tahun Terbit</th>
                {{-- Menampilkan tahun terbit buku --}}
                <td>{{ $book->year }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                {{-- Menampilkan kategori buku, jika tidak ada tampilkan '-' --}}
                <td>{{ $book->category->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Stok</th>
                {{-- Menampilkan jumlah stok buku --}}
                <td>{{ $book->stock }}</td>
            </tr>
        </table>
    {{-- Akhir dari pengecekan kosong --}}
    @endempty
</div>

{{-- Footer dari modal, berisi tombol kembali --}}
<div class="modal-footer">
    {{-- Tombol untuk kembali ke halaman sebelumnya --}}
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

{{-- Mengakhiri section content --}}
@endsection

{{-- Menambahkan stack css jika dibutuhkan di bagian layout --}}
@push('css')
@endpush

{{-- Menambahkan stack javascript jika dibutuhkan di bagian layout --}}
@push('js')
@endpush
