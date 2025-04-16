<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // function untuk menjalankan relasi dari buku ke kategori
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // app/Models/Category.php
    // fillable yang menunjukkan value mana yang dapat dimodifikasi
    protected $fillable = ['name'];
}
