{{-- Mengecek apakah variabel $borrow kosong --}}
@empty($borrow)
    {{-- Modal ditampilkan jika data peminjaman tidak ditemukan --}}
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                {{-- Tombol untuk menutup modal --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Pesan kesalahan --}}
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data peminjaman yang Anda cari tidak ditemukan.
                </div>
                {{-- Tombol kembali ke halaman daftar peminjaman --}}
                <a href="{{ url('/borrow') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    {{-- Form untuk menghapus data peminjaman --}}
    <form action="{{ url('/borrow/' . $borrow->id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf {{-- Token untuk melindungi dari serangan CSRF --}}
        @method('DELETE') {{-- Mengubah method form menjadi DELETE --}}
        
        <div id="myModal" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Peminjaman</h5>
                    {{-- Tombol untuk menutup modal --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Konfirmasi penghapusan --}}
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                        Apakah Anda yakin ingin menghapus data peminjaman berikut?
                    </div>
                    
                    {{-- Tabel detail data peminjaman --}}
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">ID :</th>
                            <td class="col-9">{{ $borrow->id }}</td> {{-- Menampilkan ID peminjaman --}}
                        </tr>
                        <tr>
                            <th class="text-right col-3">Judul Buku :</th>
                            <td class="col-9">{{ $borrow->book->title ?? '-' }}</td> {{-- Menampilkan judul buku --}}
                        </tr>
                        <tr>
                            <th class="text-right col-3">Peminjam :</th>
                            <td class="col-9">{{ $borrow->user->name ?? '-' }}</td> {{-- Menampilkan nama peminjam --}}
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Pinjam :</th>
                            <td class="col-9">{{ $borrow->borrowed_at }}</td> {{-- Menampilkan tanggal pinjam --}}
                        </tr>
                        {{-- Tambahkan field lain jika diperlukan --}}
                    </table>
                </div>
                <div class="modal-footer">
                    {{-- Tombol untuk membatalkan aksi --}}
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    {{-- Tombol untuk menghapus data --}}
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Saat dokumen siap (DOM siap), jalankan kode ini
        $(document).ready(function() {
            // Validasi form sebelum mengirim
            $("#form-delete").validate({
                rules: {}, // Bisa ditambahkan aturan validasi jika diperlukan
                submitHandler: function(form) {
                    // Kirim data form secara AJAX
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(), // Data form diubah menjadi string query (name=value&...)
                        success: function(response) {
                            // Jika penghapusan berhasil
                            if (response.status) {
                                $('#myModal').modal('hide'); // Sembunyikan modal
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataBorrowing.ajax.reload(); // Refresh datatable (pastikan ID benar)
                            } else {
                                // Jika terjadi kesalahan
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false; // Cegah form dari pengiriman normal
                },
                errorElement: 'span', // Elemen untuk pesan error
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid'); // Tambahkan class jika ada error
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid'); // Hapus class saat valid
                }
            });
        });
    </script>
@endempty
