<!-- Form dengan method POST untuk mengirim data ke URL /booking/ajax -->
<form action="{{ url('/booking/ajax') }}" method="POST" id="form-tambah">
    @csrf <!-- Token keamanan Laravel untuk mencegah serangan CSRF -->
    
    <!-- Modal dialog berukuran besar untuk menampilkan form -->
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <!-- Header modal yang berisi judul dan tombol close -->
            <div class="modal-header">
                <h5 class="modal-title">Form Peminjaman Buku</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <!-- Body dari modal yang berisi form input -->
            <div class="modal-body">
                <!-- Input untuk memilih buku -->
                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select name="book_id" class="form-control" required>
                        <!-- Melakukan iterasi data buku dan menampilkan dalam opsi -->
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <!-- Tempat menampilkan error validasi jika ada -->
                    <small id="error-book_id" class="error-text text-danger"></small>
                </div>

                <!-- Input untuk memilih tanggal peminjaman -->
                <div class="form-group">
                    <label>Tanggal Peminjaman</label>
                    <input type="date" name="borrowed_at" class="form-control" required>
                    <small id="error-borrowed_at" class="error-text text-danger"></small>
                </div>
            </div>

            <!-- Footer dari modal yang berisi tombol -->
            <div class="modal-footer">
                <!-- Tombol untuk menutup modal -->
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <!-- Tombol untuk submit form -->
                <button type="submit" class="btn btn-primary">Pinjam</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Validasi form saat dokumen sudah siap
        $("#form-tambah").validate({
            rules: {
                // book_id dan borrowed_at wajib diisi
                book_id: { required: true },
                borrowed_at: { required: true, date: true }
            },
            submitHandler: function (form) {
                // Jika valid, kirim data via AJAX
                $.ajax({
                    url: form.action, // ambil URL dari atribut form
                    type: form.method, // method POST
                    data: $(form).serialize(), // serialize form ke format URL-encoded
                    success: function (response) {
                        if (response.status) {
                            // Jika berhasil, tutup modal dan tampilkan alert sukses
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            // Reload DataTable jika ada (opsional)
                            dataLevel?.ajax?.reload();
                        } else {
                            // Tampilkan pesan error per field jika validasi server gagal
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            // Tampilkan alert gagal
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    }
                });
                return false; // Mencegah submit default
            },
            errorElement: 'span', // Element HTML untuk error
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback'); // Tambahkan class bootstrap
                element.closest('.form-group').append(error); // Tempelkan ke form-group
            },
            highlight: function (element) {
                $(element).addClass('is-invalid'); // Tambah class saat error
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid'); // Hapus class saat valid
            }
        });
    });
</script>
