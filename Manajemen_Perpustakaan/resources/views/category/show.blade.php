@extends('layouts.template')
@section('content')

<div class="modal-header">
    <h5 class="modal-title">Detail Kategori</h5>
    
</div>
<div class="modal-body">
    @empty($category)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data kategori tidak ditemukan.
        </div>
    @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <th>Nama Kategori</th>
                <td>{{ $category->name }}</td>
            </tr>
            {{-- Tambahkan field lain jika diperlukan --}}
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
