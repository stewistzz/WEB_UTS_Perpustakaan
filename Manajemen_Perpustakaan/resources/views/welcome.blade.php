@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            {{-- admin sidebar --}}
            @if (Auth::user() && Auth::user()->role === 'admin')
                Selamat datang, Admin.
            @else
            Selamat datang, User.
            @endif
        </h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        Selamat datang, ini adalah halaman utama dari aplikasi ini.
    </div>
</div>

@endsection
