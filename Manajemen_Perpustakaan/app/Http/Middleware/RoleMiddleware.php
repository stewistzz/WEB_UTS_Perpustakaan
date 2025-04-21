<?php

// Mendefinisikan namespace tempat file middleware ini berada
namespace App\Http\Middleware;

// Mengimpor class Closure untuk menangani request selanjutnya
use Closure;
// Mengimpor class Request untuk menangani permintaan HTTP
use Illuminate\Http\Request;
// Mengimpor facade Auth untuk mengecek autentikasi pengguna
use Illuminate\Support\Facades\Auth;

// Mendefinisikan class middleware bernama RoleMiddleware
class RoleMiddleware
{
    // Fungsi handle untuk menangani proses middleware
    // Parameter:
    // - $request: data permintaan HTTP
    // - $next: Closure untuk melanjutkan ke proses selanjutnya
    // - $role: parameter role yang diperlukan untuk akses
    public function handle(Request $request, Closure $next, $role)
    {
        // Mengecek apakah user sudah login dan memiliki role yang sesuai
        if (Auth::check() && Auth::user()->role === $role) {
            // Jika iya, lanjutkan ke proses selanjutnya (controller atau middleware berikutnya)
            return $next($request);
        }

        // Jika tidak sesuai, tampilkan error 403 (Forbidden)
        abort(403, 'Akses ditolak');
    }
}
