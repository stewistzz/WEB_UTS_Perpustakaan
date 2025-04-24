<!-- Form untuk menambahkan data buku -->
<form action="{{ url('/book/ajax') }}" method="POST" id="form-tambah">
    @csrf <!-- Token CSRF untuk keamanan form -->

    <!-- Modal dialog ukuran besar untuk input buku -->
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> <!-- Kontainer utama modal -->

            <!-- Header modal -->
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Buku</h5>
                <!-- Tombol untuk menutup modal -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> <!-- simbol "x" -->
                </button>
            </div>

            <!-- Body modal (isi form input) -->
            <div class="modal-body">

                <!-- Input Judul Buku -->
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="title" class="form-control" required>
                    <small id="error-title" class="error-text form-text text-danger"></small> <!-- Tempat pesan error -->
                </div>

                <!-- Input Penulis Buku -->
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="author" class="form-control" required>
                    <small id="error-author" class="error-text form-text text-danger"></small>
                </div>

                <!-- Input Penerbit Buku -->
                <div class="form-group">
                    <label>Penerbit</label>
                    <input type="text" name="publisher" class="form-control" required>
                    <small id="error-publisher" class="error-text form-text text-danger"></small>
                </div>

                <!-- Input Tahun Terbit -->
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="year" class="form-control" required>
                    <small id="error-year" class="error-text form-text text-danger"></small>
                </div>

                <!-- Dropdown Kategori Buku -->
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <!-- Loop untuk menampilkan kategori dari server -->
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <small id="error-category_id" class="error-text form-text text-danger"></small>
                </div>

                <!-- Input jumlah stok buku -->
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" class="form-control" required>
                    <small id="error-stock" class="error-text form-text text-danger"></small>
                </div>

            </div>

            <!-- Footer modal dengan tombol -->
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button> <!-- Submit form -->
            </div>

        </div>
    </div>
</form>

<!-- Script jQuery untuk validasi dan pengiriman form secara AJAX -->
<script>
    $(document).ready(function () {
        // Inisialisasi validasi form dengan plugin jQuery Validate
        $("#form-tambah").validate({
            rules: {
                // Aturan validasi tiap field
                title: { required: true, minlength: 2 },
                author: { required: true },
                publisher: { required: true },
                year: { required: true, digits: true },
                category_id: { required: true },
                stock: { required: true, digits: true, min: 0 }
            },
            // Jika form valid, jalankan AJAX submit
            submitHandler: function (form) {
                $.ajax({
                    url: form.action, // URL tujuan dari attribute action
                    type: form.method, // Method POST dari attribute method
                    data: $(form).serialize(), // Serialize data form menjadi format URL encoded
                    success: function (response) {
                        if (response.status) {
                            // Jika berhasil, tutup modal dan tampilkan pesan sukses
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataBook.ajax.reload(); // Reload data di DataTable
                        } else {
                            // Tampilkan error per field jika validasi gagal
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]); // Tampilkan pesan error sesuai field
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false; // Hindari reload page
            },
            errorElement: 'span', // Elemen untuk menampilkan pesan error
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback'); // Tambah class untuk styling Bootstrap
                element.closest('.form-group').append(error); // Tempatkan error di bawah field
            },
            highlight: function (element) {
                $(element).addClass('is-invalid'); // Tandai field error dengan class Bootstrap
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid'); // Hapus penanda error jika valid
            }
        });
    });
</script>
