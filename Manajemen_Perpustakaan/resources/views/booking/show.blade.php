{{-- Menggunakan layout template utama --}}
@extends('layouts.template')

{{-- Mulai section 'content' untuk mengisi bagian konten utama pada template --}}
@section('content')

<div class="modal-header">
    {{-- Judul modal ditampilkan --}}
    <h5 class="modal-title">Detail Booking</h5>
</div>

<div class="modal-body">
    {{-- Jika variabel $booking kosong, tampilkan pesan error --}}
    @empty($booking)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data peminjaman tidak ditemukan.
        </div>
    @else
        {{-- Jika data booking tersedia, tampilkan dalam bentuk tabel --}}
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                {{-- Menampilkan ID booking --}}
                <td>{{ $booking->id }}</td>
            </tr>
            <tr>
                <th>Nama Pengguna</th>
                {{-- Menampilkan nama pengguna, jika tidak ada tampilkan "-" --}}
                <td>{{ $booking->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Judul Buku</th>
                {{-- Menampilkan judul buku, jika tidak ada tampilkan "-" --}}
                <td>{{ $booking->book->title ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Peminjaman</th>
                {{-- Menampilkan tanggal peminjaman --}}
                <td>{{ $booking->borrowed_at }}</td>
            </tr>
            <tr>
                <th>Jatuh Tempo</th>
                {{-- Menampilkan tanggal jatuh tempo pengembalian --}}
                <td>{{ $booking->due_date }}</td>
            </tr>
            <tr>
                <th>Tanggal Pengembalian</th>
                {{-- Menampilkan tanggal pengembalian, jika sudah dikembalikan --}}
                <td>{{ $booking->returned_at }}</td>
            </tr>
            <tr>
                <th>Status</th>
                {{-- Menampilkan status peminjaman (misalnya: Dipinjam, Dikembalikan, Terlambat) --}}
                <td>{{ $booking->status }}</td>
            </tr>
        </table>
    @endempty
</div>

<div class="modal-footer">
    {{-- Tombol kembali ke halaman sebelumnya --}}
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

{{-- Mengakhiri section 'content' --}}
@endsection

{{-- Tempat untuk menyisipkan file CSS tambahan jika diperlukan --}}
@push('css')
@endpush

{{-- Tempat untuk menyisipkan file JavaScript tambahan jika diperlukan --}}
@push('js')
@endpush
