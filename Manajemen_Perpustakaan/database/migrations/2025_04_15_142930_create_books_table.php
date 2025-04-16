<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            // struktur tabel dari buku di perpustakaan
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->year('year');
            $table->unsignedBigInteger('category_id')->index(); // indexing untuk ForeignKey
            
            $table->integer('stock');
            $table->timestamps();
            
            // mengambil foreign key dari id categiry dari kolom kategori
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
