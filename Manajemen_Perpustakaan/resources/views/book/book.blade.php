{{-- Meng-extend template utama --}}
@extends('layouts.template')

{{-- Menentukan bagian konten utama yang akan dimasukkan ke dalam layout --}}
@section('content')
    <div class="card card-outline card-primary">
        {{-- Header kartu --}}
        <div class="card-header">
            {{-- Menampilkan judul halaman, jika $page->title tersedia --}}
            <h3 class="card-title">{{ $page->title ?? 'Daftar Buku' }}</h3>
            <div class="card-tools">
                {{-- Tombol untuk membuka form tambah buku via modal --}}
                <button onclick="modalAction('{{ url('/book/create_ajax') }}')" class="btn btn-sm btn-primary mt-1">Tambah Buku</button>
            </div>
        </div>

        <div class="card-body">
            {{-- Menampilkan pesan sukses jika ada --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Menampilkan pesan error jika ada --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Tabel untuk menampilkan data buku --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_book">
                <thead>
                    <tr>
                        <th>No</th> {{-- Nomor urut --}}
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Aksi</th> {{-- Kolom aksi untuk edit/hapus --}}
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal kosong, isinya akan dimuat secara dinamis --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    </div>
@endsection

{{-- Stack untuk custom CSS (kosong, tapi bisa ditambahkan jika diperlukan) --}}
@push('css')
@endpush

{{-- Stack untuk custom JavaScript --}}
@push('js')
    <script>
        // Fungsi untuk menampilkan modal dan memuat isi dari URL yang diberikan
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show'); // Tampilkan modal setelah isinya dimuat
            });
        }

        var dataBook;

        // Inisialisasi DataTables saat dokumen siap
        $(document).ready(function() {
            dataBook = $('#table_book').DataTable({
                processing: true, // Tampilkan animasi loading
                serverSide: true, // Data akan diambil dari server (bukan client-side)
                ajax: {
                    url: "{{ url('book/list') }}", // URL untuk mengambil data
                    type: "POST", // Metode pengiriman data
                    data: function(d) {
                        // Anda bisa menambahkan parameter di sini jika diperlukan
                    }
                },
                // Kolom-kolom yang ditampilkan di tabel
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false }, // Nomor urut otomatis dari server
                    { data: "title" }, // Judul buku
                    { data: "author" }, // Penulis buku
                    { data: "publisher" }, // Penerbit buku
                    { data: "year" }, // Tahun terbit
                    { data: "category_id" }, // ID kategori, bisa diganti dengan nama kategori di controller
                    { data: "stock", className: "text-center" }, // Stok buku
                    { data: "aksi", orderable: false, searchable: false } // Tombol aksi (edit/hapus)
                ]
            });
        });
    </script>
@endpush
