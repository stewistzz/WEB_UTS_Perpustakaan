@extends('layouts.template')
@section('content')

<div class="modal-header">
    <h5 class="modal-title">Detail Peminjaman</h5>
</div>

<div class="modal-body">
    @empty($borrow)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data peminjaman tidak ditemukan.
        </div>
    @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                <td>{{ $borrow->id }}</td>
            </tr>
            <tr>
                <th>Nama Pengguna</th>
                <td>{{ $borrow->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Judul Buku</th>
                <td>{{ $borrow->book->title ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Peminjaman</th>
                <td>{{ $borrow->borrowed_at }}</td>
            </tr>
            <tr>
                <th>Jatuh Tempo</th>
                <td>{{ $borrow->due_date }}</td>
            </tr>
            <tr>
                <th>Tanggal Pengembalian</th>
                <td>{{ $borrow->returned_at ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $borrow->status }}</td>
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
