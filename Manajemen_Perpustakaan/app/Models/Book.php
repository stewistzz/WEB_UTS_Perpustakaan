<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // app/Models/Book.php
    // membuat spesifikasi dari atribut yang dapat diisi
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year',
        'category',
        'stock'
    ];
}
