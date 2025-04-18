@extends('layouts.template')
@section('content')

<div class="modal-header">
    <h5 class="modal-title">Detail Buku</h5>
</div>

<div class="modal-body">
    @empty($book)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data buku tidak ditemukan.
        </div>
    @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                <td>{{ $book->id }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td>{{ $book->title }}</td>
            </tr>
            <tr>
                <th>Penulis</th>
                <td>{{ $book->author }}</td>
            </tr>
            <tr>
                <th>Penerbit</th>
                <td>{{ $book->publisher }}</td>
            </tr>
            <tr>
                <th>Tahun Terbit</th>
                <td>{{ $book->year }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $book->category->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Stok</th>
                <td>{{ $book->stock }}</td>
            </tr>
        </table>
    @endempty
</div>

<div class="modal-footer">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

@endsection

@push('css')
@endpush
@push('js')
@endpush
