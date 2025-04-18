@extends('layouts.template')
@section('content')

<div class="modal-header">
    <h5 class="modal-title">Detail Pengguna</h5>
</div>
<div class="modal-body">
    @empty($user)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data pengguna tidak ditemukan.
        </div>
    @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                <td>{{ $user->id }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Password</th>
                <td>{{ $user->password }}</td>
            </tr>
            <tr>
                <th>Role</th>
                <td>{{ $user->role }}</td>
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
