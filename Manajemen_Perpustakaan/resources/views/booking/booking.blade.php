{{-- Menggunakan layout utama dari file template --}}
@extends('layouts.template')

{{-- Mulai bagian konten dari layout --}}
@section('content')
    {{-- Membuat card dengan border berwarna primary --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            {{-- Menampilkan judul halaman dari variabel $page->title jika ada, jika tidak, tampilkan default --}}
            <h3 class="card-title">{{ $page->title ?? 'Daftar Peminjaman' }}</h3>

            {{-- Tempat untuk tombol atau alat lain --}}
            <div class="card-tools">
                {{-- Tombol untuk membuka modal tambah peminjaman, memanggil fungsi modalAction() --}}
                <button onclick="modalAction('{{ url('/booking/create_ajax') }}')" class="btn btn-sm btn-primary mt-1">Tambah Peminjaman</button>
            </div>
        </div>
        <div class="card-body">
            {{-- Menampilkan pesan sukses jika ada di sesi --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Menampilkan pesan error jika ada di sesi --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Tabel untuk menampilkan daftar peminjaman --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_borrowing">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pengguna</th>
                        <th>ID Buku</th>
                        <th>Tgl Peminjaman</th>
                        <th>Jatuh Tempo</th>
                        {{-- <th>Tgl Pengembalian</th> --}} {{-- Baris ini dikomentari, bisa diaktifkan jika diperlukan --}}
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                {{-- Body akan diisi oleh DataTables secara dinamis --}}
            </table>
        </div>
    </div>

    {{-- Modal kosong yang akan diisi dengan konten dinamis saat tombol diklik --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    </div>
@endsection

{{-- Bagian untuk menambahkan CSS tambahan (jika ada) --}}
@push('css')
@endpush

{{-- Bagian untuk menambahkan script JavaScript tambahan --}}
@push('js')
    <script>
        // Fungsi untuk membuka modal dan memuat konten dari URL secara AJAX
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show'); // Tampilkan modal setelah konten dimuat
            });
        }

        var dataBorrowing; // Variabel untuk menyimpan instance DataTables

        $(document).ready(function() {
            // Inisialisasi DataTable pada tabel dengan ID table_borrowing
            dataBorrowing = $('#table_borrowing').DataTable({
                processing: true, // Tampilkan indikator loading saat proses data
                serverSide: true, // Menggunakan server-side processing (data diambil dari server)
                ajax: {
                    url: "{{ url('booking/list') }}", // URL sumber data
                    type: "POST" // Metode HTTP
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false }, // Nomor urut baris
                    { data: "user_id" }, // Kolom ID pengguna
                    { data: "book_id" }, // Kolom ID buku
                    { data: "borrowed_at", className: "text-center" }, // Tanggal peminjaman
                    { data: "due_date", className: "text-center" }, // Tanggal jatuh tempo
                    // { data: "returned_at", className: "text-center" }, // Kolom tanggal pengembalian, bisa diaktifkan jika diperlukan
                    { data: "status", className: "text-center" }, // Status peminjaman
                    { data: "aksi", orderable: false, searchable: false } // Kolom untuk tombol aksi (edit, hapus, dll.)
                ]
            });
        });
    </script>
@endpush
