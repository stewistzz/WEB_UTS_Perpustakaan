{{-- Mengecek apakah variabel $book kosong --}}
@empty($book)
    {{-- Jika kosong, tampilkan modal error --}}
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Judul modal --}}
                <h5 class="modal-title">Kesalahan</h5>
                {{-- Tombol untuk menutup modal --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> {{-- Simbol X --}}
                </button>
            </div>
            <div class="modal-body">
                {{-- Menampilkan pesan error --}}
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data buku yang Anda cari tidak ditemukan.
                </div>
                {{-- Tombol untuk kembali ke halaman daftar buku --}}
                <a href="{{ url('/book') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    {{-- Jika $book tidak kosong, tampilkan modal konfirmasi penghapusan --}}
    <form action="{{ url('/book/' . $book->id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf {{-- Token keamanan CSRF --}}
        @method('DELETE') {{-- Menggunakan metode DELETE untuk form --}}
        
        <div id="myModal" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- Judul modal --}}
                    <h5 class="modal-title">Hapus Buku</h5>
                    {{-- Tombol untuk menutup modal --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span> {{-- Simbol X --}}
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Pesan peringatan konfirmasi --}}
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                        Apakah Anda yakin ingin menghapus data buku berikut?
                    </div>
                    {{-- Menampilkan detail buku yang akan dihapus --}}
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">ID :</th>
                            <td class="col-9">{{ $book->id }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Judul Buku :</th>
                            <td class="col-9">{{ $book->title }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penulis :</th>
                            <td class="col-9">{{ $book->author }}</td>
                        </tr>
                        {{-- Tambahkan baris tambahan jika ada informasi lain yang ingin ditampilkan --}}
                    </table>
                </div>
                <div class="modal-footer">
                    {{-- Tombol batal --}}
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    {{-- Tombol konfirmasi hapus --}}
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>

    {{-- JavaScript untuk validasi dan AJAX --}}
    <script>
        $(document).ready(function() {
            $("#form-delete").validate({ // Inisialisasi validasi form
                rules: {}, // Bisa tambahkan validasi spesifik di sini
                submitHandler: function(form) {
                    // Kirim data secara AJAX
                    $.ajax({
                        url: form.action, // URL dari form
                        type: form.method, // Metode POST/DELETE
                        data: $(form).serialize(), // Serialize form data
                        success: function(response) {
                            if (response.status) {
                                // Tutup modal jika berhasil
                                $('#myModal').modal('hide');
                                // Tampilkan pesan sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                // Reload data tabel buku
                                dataBooks.ajax.reload(); // Sesuaikan dengan ID datatable
                            } else {
                                // Reset error sebelumnya
                                $('.error-text').text('');
                                // Tampilkan pesan error pada field yang sesuai
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                // Tampilkan alert error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false; // Hindari reload halaman
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    // Tempatkan error di bawah field input
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    // Tambahkan class untuk input error
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    // Hapus class jika input valid kembali
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
