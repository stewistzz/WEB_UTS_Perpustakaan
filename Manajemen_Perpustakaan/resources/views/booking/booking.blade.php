@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title ?? 'Daftar Peminjaman' }}</h3>
            <div class="card-tools">
                {{-- Tambahkan tombol jika perlu tambah data peminjaman --}}
                <button onclick="modalAction('{{ url('/booking/create_ajax') }}')" class="btn btn-sm btn-primary mt-1">Tambah Peminjaman</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_borrowing">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pengguna</th>
                        <th>ID Buku</th>
                        <th>Tgl Peminjaman</th>
                        <th>Jatuh Tempo</th>
                        {{-- <th>Tgl Pengembalian</th> --}}
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataBorrowing;
        $(document).ready(function() {
            dataBorrowing = $('#table_borrowing').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('booking/list') }}",
                    type: "POST"
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "user_id" },
                    { data: "book_id" },
                    { data: "borrowed_at", className: "text-center" },
                    { data: "due_date", className: "text-center" },
                    // { data: "returned_at", className: "text-center" },
                    { data: "status", className: "text-center" },
                    { data: "aksi", orderable: false, searchable: false } // aktifkan jika kamu ingin tombol aksi
                ]
            });
        });
    </script>
@endpush
