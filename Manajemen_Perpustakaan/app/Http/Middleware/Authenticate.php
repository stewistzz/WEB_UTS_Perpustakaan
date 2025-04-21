<?php

// Mendeklarasikan namespace dari file ini agar bisa digunakan dengan autoloading PSR-4
namespace App\Http\Middleware;

// Menggunakan Middleware bawaan dari Laravel untuk autentikasi
use Illuminate\Auth\Middleware\Authenticate as Middleware;
// Mengimpor class Request untuk menangani request dari user
use Illuminate\Http\Request;

// Membuat class Authenticate yang merupakan turunan dari Middleware autentikasi Laravel
class Authenticate extends Middleware
{
    /**
     * Method ini digunakan untuk menentukan ke mana user akan diarahkan
     * jika mereka belum login (belum terautentikasi).
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request mengharapkan respon dalam format JSON (misalnya API),
        // maka tidak perlu redirect (return null).
        // Jika tidak, arahkan ke route bernama 'login'.
        return $request->expectsJson() ? null : route('login');
    }
}
