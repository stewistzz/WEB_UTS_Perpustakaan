@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                @if (Auth::user() && Auth::user()->role === 'admin')
                    Selamat datang, Admin.
                @else
                    Selamat datang, User.
                @endif
            </h3>
        </div>
        <div class="card-body">
            Selamat datang, ini adalah halaman utama dari aplikasi ini.
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $bookCount }}</h3>
                    <p>Total Buku</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book fa-2x"></i>
                </div>
                <a href="{{ url('/book') }}" class="small-box-footer">Lihat Buku <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $categoryCount }}</h3>
                    <p>Total Kategori</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group fa-2x"></i>
                </div>
                <a href="{{ url('/category') }}" class="small-box-footer">Lihat Kategori <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $userCount }}</h3>
                    <p>Total Pengguna</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <a href="{{ url('/user') }}" class="small-box-footer">Lihat Pengguna <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $borrowCount }}</h3>
                    <p>Total Peminjaman</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book-reader fa-2x"></i>
                </div>
                <a href="{{ url('/admin/borrowing') }}" class="small-box-footer">Lihat Peminjaman <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@endsection
