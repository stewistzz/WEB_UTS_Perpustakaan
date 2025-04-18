@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title ?? 'Daftar Buku' }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/book/create_ajax') }}')" class="btn btn-sm btn-primary mt-1">Tambah Buku</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            

            <table class="table table-bordered table-striped table-hover table-sm" id="table_book">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Stok</th>
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

        var dataBook;
        $(document).ready(function() {
            dataBook = $('#table_book').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('book/list') }}",
                    type: "POST",
                    data: function(d) {

                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "title" },
                    { data: "author" },
                    { data: "publisher" },
                    { data: "year" },
                    { data: "category_id" }, // pastikan ini tersedia di controller
                    { data: "stock", className: "text-center" },
                    { data: "aksi", orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
