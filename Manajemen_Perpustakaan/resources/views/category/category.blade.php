@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title ?? 'Kategori Buku' }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/category/create_ajax') }}')" class="btn btn-sm btn-primary mt-1">Tambah Kategori</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- table --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_category">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
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
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var dataCategory;
        $(document).ready(function () {
            dataCategory = $('#table_category').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('category/list') }}",
                    type: "POST",
                    data: function (d) {
                        // d.category_id = $('#category_id').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "name", orderable: true, searchable: true },
                    { data: "aksi", orderable: false, searchable: false }
                ]
            });

        });
    </script>
@endpush
