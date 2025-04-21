<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function loginForm()
    {
        return view('login');
    }

    // Proses login pengguna
    public function login(Request $request)
    {
        // Mengambil input email dan password dari form
        $credentials = $request->only('email', 'password');

        // Mengecek apakah kredensial cocok dengan data di database
        if (Auth::attempt($credentials)) {
            // Jika berhasil login, redirect ke halaman utama (atau halaman sebelumnya yang diminta)
            return redirect()->intended('/');
        }

        // Jika gagal login, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Proses logout pengguna
    public function logout()
    {
        // Logout dari sistem
        Auth::logout();

        // Redirect ke halaman login setelah logout
        return redirect('/login');
    }
}

